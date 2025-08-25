<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCategoryRequest;
use App\Models\Category;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use App\Services\SlugService;
use Spatie\Sluggable\HasSlug;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): Factory|View
    {
        $categories = Category::orderBy('created_at', 'desc')->paginate(5);

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): Factory|View
    {
        $categories = Category::all();

        return view('admin.categories.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCategoryRequest $request)
    {
        // Retrieve validated data from the request
        $data = $request->validated();
        
        // Handle file upload before creating category
        if ($request->hasFile('icon')) {
            $icon = $request->file('icon');
            $iconFileName = time() . '_' . $icon->getClientOriginalName();
            $iconPath = $icon->storeAs('categories', $iconFileName, 'public');
            $data['icon'] = $iconPath;
        }
        
        // Create the category
        $category = Category::create($data);
        
        // Loop through each locale to update SEO data for translations
        foreach (config('app.locales') as $locale) {
            // Retrieve the SEO model for the translation
            $seo = $category->translate($locale)->seo;    
            // Prepare SEO data
            $seoData = [
                'title' => $data[$locale]['title'],
                'description' => $data[$locale]['description'],
                'image' => isset($data[$locale]['image']) ? $data[$locale]['image'] : null,
                'author' => isset($data[$locale]['author']) ? $data[$locale]['author'] : null,
                'robots' => isset($data[$locale]['robots']) ? $data[$locale]['robots'] : null,
                // Add more fields here as needed
            ];     
            // Update SEO data
            $seo->update($seoData);
        }
        
        return redirect()->route('categories.index', app()->getLocale());
    }
    
    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id): Factory|View
    {
      
        $category = Category::findOrFail($id);
        $categories = Category::where('id', '!=', $category->id)->get();

        return view('admin.categories.edit', compact('category', 'categories'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $data = $request->all();
        
        // Handle file upload
        if ($request->hasFile('icon')) {
            // Delete old icon if exists
            if ($category->icon && Storage::disk('public')->exists($category->icon)) {
                Storage::disk('public')->delete($category->icon);
            }
            
            $icon = $request->file('icon');
            $iconFileName = time() . '_' . $icon->getClientOriginalName();
            $iconPath = $icon->storeAs('categories', $iconFileName, 'public');
            $data['icon'] = $iconPath;
        }
        
        $category->update($data);
        

        return redirect()->route('categories.index', [app()->getLocale()]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('categories.index', app()->getLocale());
    }

    public function deleteIcon($id)
    {
        $category = Category::findOrFail($id);

        if ($category->icon) {
            // Delete the icon from storage
            Storage::disk('public')->delete($category->icon);

            // Remove the icon from the category
            $category->update(['icon' => null]);
        }

        return response()->json(['success' => 'Files Deleted']);
    }
}
