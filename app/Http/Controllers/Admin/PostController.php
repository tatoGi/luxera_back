<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\Post;
use App\Models\PostAttribute;
use App\Services\PageTypeService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display posts for a specific page
     */
    public function index(Page $page)
    {
        if (!$page->supportsPost()) {
            return redirect()->back()->with('error', 'This page type does not support posts.');
        }

        $posts = $page->posts()->with('attributes')->paginate(10);
        $pageTypeConfig = $page->getPageTypeConfig();
        
        return view('admin.posts.index', compact('page', 'posts', 'pageTypeConfig'));
    }

    /**
     * Show the form for creating a new post
     */
    public function create(Page $page)
    {
        if (!$page->supportsPost()) {
            return redirect()->back()->with('error', 'This page type does not support posts.');
        }

        $pageTypeConfig = $page->getPageTypeConfig();
        $translatableAttributes = PageTypeService::getTranslatableAttributes($page->type_id);
        $nonTranslatableAttributes = PageTypeService::getNonTranslatableAttributes($page->type_id);
        $categories = \App\Models\Category::where('active', true)->get();
        
        return view('admin.posts.create', compact('page', 'pageTypeConfig', 'translatableAttributes', 'nonTranslatableAttributes', 'categories'));
    }

    /**
     * Store a newly created post
     */
    public function store(Request $request, Page $page)
    {
        if (!$page->supportsPost()) {
            return redirect()->back()->with('error', 'This page type does not support posts.');
        }

        // Get validation rules from PageTypeService, filtered by post type for homepage posts
        if ($page->type_id == 1 && $request->has('post_type')) {
            $rules = PageTypeService::getFilteredValidationRules($page->type_id, $request->post_type);
        } else {
            $rules = PageTypeService::getValidationRules($page->type_id);
        }
        $rules['published_at'] = 'nullable|date';
        $rules['active'] = 'boolean';
        $rules['sort_order'] = 'nullable|integer';
        $rules['category_id'] = 'nullable|exists:categories,id';
        
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Create the post
        $post = new Post();
        $post->page_id = $page->id;
        $post->category_id = $request->input('category_id');
        $post->active = $request->boolean('active', true);
        $post->sort_order = $request->input('sort_order', 0);
        $post->published_at = $request->input('published_at') ? now() : null;
        
        // Save the post and check if it was successful
        if (!$post->save()) {
            return redirect()->back()->with('error', 'Failed to create post')->withInput();
        }
        
        // Verify the post has an ID
        if (!$post->id) {
            return redirect()->back()->with('error', 'Post was not saved properly')->withInput();
        }

        // Save attributes
        try {
            $this->savePostAttributes($post, $request, $page->type_id);
        } catch (\Exception $e) {
            throw $e; // Re-throw to see the error
        }

        return redirect()->route('admin.pages.posts.index', ['locale' => app()->getLocale(), 'page' => $page->id])
            ->with('success', 'Post created successfully!');
    }

    /**
     * Show the form for editing a post
     */
    public function edit(Page $page, Post $post)
    {
        if (!$page->supportsPost() || $post->page_id !== $page->id) {
            return redirect()->back()->with('error', 'Invalid post or page.');
        }

        $pageTypeConfig = $page->getPageTypeConfig();
        $translatableAttributes = PageTypeService::getTranslatableAttributes($page->type_id);
        $nonTranslatableAttributes = PageTypeService::getNonTranslatableAttributes($page->type_id);
        
        // Load existing attributes
        $existingAttributes = [];
        
        // Load attributes directly from database to ensure we get all data
        $attributes = PostAttribute::where('post_id', $post->id)->get();
        
        foreach ($attributes as $attr) {
            if ($attr->locale) {
                $existingAttributes[$attr->locale][$attr->attribute_key] = $attr->attribute_value;
            } else {
                $existingAttributes[$attr->attribute_key] = $attr->attribute_value;
            }
        }
        
        $categories = \App\Models\Category::where('active', true)->get();
    
        return view('admin.posts.edit', compact('page', 'post', 'pageTypeConfig', 'translatableAttributes', 'nonTranslatableAttributes', 'existingAttributes', 'categories'));
    }

    /**
     * Update a post
     */
    public function update(Request $request, Page $page, Post $post)
    {
        if (!$page->supportsPost() || $post->page_id !== $page->id) {
            return redirect()->back()->with('error', 'Invalid post or page.');
        }

        // Get validation rules from PageTypeService
        // For homepage posts (type_id == 1), filter rules by post_type to avoid validating unrelated fields
        if ($page->type_id == 1) {
            // Prefer the incoming post_type, otherwise use the stored attribute value
            $effectivePostType = $request->input('post_type');
            if (!$effectivePostType) {
                $effectivePostType = PostAttribute::where('post_id', $post->id)
                    ->where('attribute_key', 'post_type')
                    ->whereNull('locale')
                    ->value('attribute_value');
            }
            if ($effectivePostType) {
                $rules = PageTypeService::getFilteredValidationRules($page->type_id, $effectivePostType);
            } else {
                $rules = PageTypeService::getValidationRules($page->type_id);
            }
        } else {
            $rules = PageTypeService::getValidationRules($page->type_id);
        }
        // For update: make image fields optional to allow keeping existing image without re-upload
        $nonTranslatableAttributes = PageTypeService::getNonTranslatableAttributes($page->type_id);
        foreach ($nonTranslatableAttributes as $key => $config) {
            if (($config['type'] ?? null) === 'image') {
                // Override image rules to be nullable on update
                $rules[$key] = 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp';
            }
        }
        $rules['published_at'] = 'nullable|date';
        $rules['active'] = 'boolean';
        $rules['sort_order'] = 'nullable|integer';
        $rules['category_id'] = 'nullable|exists:categories,id';
        
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Update the post
        $post->category_id = $request->input('category_id');
        $post->active = $request->boolean('active', true);
        $post->sort_order = $request->input('sort_order', 0);
        $post->published_at = $request->input('published_at') ? now() : null;
        $post->save();

        // Update attributes
        $this->savePostAttributes($post, $request, $page->type_id);

        return redirect()->route('admin.pages.posts.index', ['locale' => app()->getLocale(), 'page' => $page->id])
            ->with('success', 'Post updated successfully!');
    }

    /**
     * Remove a post
     */
    public function destroy(Page $page, Post $post)
    {
        if (!$page->supportsPost() || $post->page_id !== $page->id) {
            return redirect()->back()->with('error', 'Invalid post or page.');
        }

        // Delete associated files
        $this->deletePostFiles($post, $page->type_id);
        
        // Delete the post (attributes will be deleted via cascade)
        $post->delete();

        return redirect()->route('admin.pages.posts.index', ['locale' => app()->getLocale(), 'page' => $page->id])
            ->with('success', 'Post deleted successfully!');
    }

    /**
     * Save post attributes
     */
    private function savePostAttributes(Post $post, Request $request, $pageTypeId)
    {
        // Debug: Check if post has ID
        if (!$post->id) {
            throw new \Exception('Post ID is null in savePostAttributes');
        }
        
        $translatableAttributes = PageTypeService::getTranslatableAttributes($pageTypeId);
        $nonTranslatableAttributes = PageTypeService::getNonTranslatableAttributes($pageTypeId);
        
        // Save translatable attributes
        foreach ($translatableAttributes as $key => $config) {
            foreach (config('app.locales') as $locale) {
                $value = $request->input("{$locale}.{$key}");
                
                if ($value !== null) {
                    $attribute = PostAttribute::updateOrCreate(
                        [
                            'post_id' => $post->id,
                            'attribute_key' => $key,
                            'locale' => $locale
                        ],
                        [
                            'attribute_value' => $value
                        ]
                    );
                }
            }
        }
        
        // Save non-translatable attributes
        foreach ($nonTranslatableAttributes as $key => $config) {
            $value = $request->input($key);

            // Handle image removal request
            if (($config['type'] ?? null) === 'image' && $request->boolean('remove_' . $key)) {
                $oldAttribute = PostAttribute::where('post_id', $post->id)
                    ->where('attribute_key', $key)
                    ->whereNull('locale')
                    ->first();

                if ($oldAttribute && $oldAttribute->attribute_value) {
                    Storage::disk('public')->delete($oldAttribute->attribute_value);
                }

                // Delete attribute row entirely
                if ($oldAttribute) {
                    $oldAttribute->delete();
                }

                // Skip further handling for this key since it's removed
                continue;
            }

            // Handle file uploads
            if (($config['type'] ?? null) === 'image' && $request->hasFile($key)) {
                // Delete old file if exists
                $oldAttribute = PostAttribute::where('post_id', $post->id)
                    ->where('attribute_key', $key)
                    ->whereNull('locale')
                    ->first();
                    
                if ($oldAttribute && $oldAttribute->attribute_value) {
                    Storage::disk('public')->delete($oldAttribute->attribute_value);
                }
                
                // Store new file
                $file = $request->file($key);
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('posts', $filename, 'public');
                $value = $path;
            }

            if ($value !== null) {
                $attribute = PostAttribute::updateOrCreate(
                    [
                        'post_id' => $post->id,
                        'attribute_key' => $key,
                        'locale' => null
                    ],
                    [
                        'attribute_value' => $value
                    ]
                );
            }
        }
        
        // Always save post_type for homepage posts, regardless of filtering
        if ($pageTypeId == 1 && $request->has('post_type')) {
            PostAttribute::updateOrCreate(
                [
                    'post_id' => $post->id,
                    'attribute_key' => 'post_type',
                    'locale' => null
                ],
                [
                    'attribute_value' => $request->input('post_type')
                ]
            );
        }
    }
    private function deletePostFiles(Post $post, $pageTypeId)
    {
        $nonTranslatableAttributes = PageTypeService::getNonTranslatableAttributes($pageTypeId);
        
        foreach ($nonTranslatableAttributes as $key => $config) {
            if ($config['type'] === 'image') {
                $attribute = PostAttribute::where('post_id', $post->id)
                    ->where('attribute_key', $key)
                    ->whereNull('locale')
                    ->first();
                    
                if ($attribute && $attribute->attribute_value) {
                    Storage::disk('public')->delete($attribute->attribute_value);
                }
            }
        }
    }

    /**
     * Handle image upload for TinyMCE editor
     */
    public function uploadImage(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
        ]);

        try {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('editor-images', $filename, 'public');

            return response()->json([
                'location' => asset('storage/' . $path)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Image upload failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
