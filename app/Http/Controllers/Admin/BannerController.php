<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\BannerImage;
use App\Models\Page;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BannerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $banners = Banner::with('pages')->paginate(5);

        return view('admin.banners.index', compact('banners'));
    }

    /**
     * Show the form for creating a new banner.
     */
    public function create($page_id = null)
    {
        $banners = Banner::all();
        $bannerTypes = $this->bannerTypes();
        return view('admin.banners.create', compact('banners', 'bannerTypes', 'page_id'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $data = $request->except(['images']);

        // Create the banner
        $banner = Banner::create($data);

        // Handle multiple images upload
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . $image->getClientOriginalName();
                $path = $image->storeAs('banners', $imageName, 'public');

                $bannerImage = new BannerImage();
                $bannerImage->image_name = $path;
                $bannerImage->banner_id = $banner->id;
                $bannerImage->save();
            }
        }

        // Attach to page if page_id is provided
        if ($request->page_id && $request->page_id) {
            $banner->pages()->attach($request->page_id, ['sort' => 0]);
        }

        // Check if we came from page management and redirect accordingly
        if ($request->has('page_id') && $request->page_id) {
            return redirect()->route('admin.pages.management.manage', [
                'locale' => app()->getLocale(),
                'page' => $request->page_id
            ])->with('success', 'Banner created and attached to page successfully!');
        }

        return redirect()->route('banners.index', [app()->getLocale()])
            ->with('success', 'Banner created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $bannerTypes = $this->bannerTypes();
        $banner = Banner::findOrFail($id);
        $page_id = request('page_id'); // Get page_id from query parameter
        return view('admin.banners.edit', compact('banner', 'bannerTypes', 'page_id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $banner = Banner::findOrFail($id);
        
        // Update banner data
        $updateData = $request->except(['images']);
        $banner->update($updateData);

        // Handle additional images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . $image->getClientOriginalName();
                $path = $image->storeAs('banners', $imageName, 'public');

                $bannerImage = new BannerImage();
                $bannerImage->image_name = $path;
                $bannerImage->banner_id = $banner->id;
                $bannerImage->save();
            }
        }

        // Check if we came from page management and redirect accordingly
        if ($request->has('page_id') && $request->page_id) {
            return redirect()->route('admin.pages.management.manage', [
                'locale' => app()->getLocale(),
                'page' => $request->page_id
            ])->with('success', 'Banner updated successfully!');
        }
        
        return redirect()->route('banners.index', [app()->getLocale()])
            ->with('success', 'Banner updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);
        
        // Delete all banner images
        foreach ($banner->images as $image) {
            if (Storage::disk('public')->exists($image->image_name)) {
                Storage::disk('public')->delete($image->image_name);
            }
        }
        
        $banner->delete();
        
        // Check if we came from page management and redirect accordingly
        if ($request->has('page_id') && $request->page_id) {
            return redirect()->route('admin.pages.management.manage', [
                'locale' => app()->getLocale(),
                'page' => $request->page_id
            ])->with('success', 'Banner deleted successfully!');
        }
        
        return redirect()->route('banners.index', app()->getLocale())
            ->with('success', 'Banner deleted successfully!');
    }

    public function deleteImage(Request $request, $image_id)
    {
        $image = BannerImage::findOrFail($image_id);
        Storage::delete('banners/'.$image->image_name);
        $image->delete();
        return response()->json(['success' => 'Files Deleted']);
    }

    /**
     * Get banner types from config
     */
    protected function bannerTypes()
    {
        return collect(Config::get('bannerTypes'))->sortBy(function ($value, $key) {
            return $value['id'];
        });
    }
}
