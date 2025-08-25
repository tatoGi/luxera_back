<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Banner;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class PageManagementController extends Controller
{
    /**
     * Display a listing of all pages with their banners and products.
     */
    public function index()
    {
        $pages = Page::with(['banners', 'products'])->paginate(10);
        return view('admin.pages.management.index', compact('pages'));
    }



    /**
     * Show the form for managing a page's banners and products.
     */
    public function manage(Page $page)
    {
        // Get all banners not already attached to this page
        $availableBanners = Banner::whereDoesntHave('pages', function($query) use ($page) {
            $query->where('page_id', $page->id);
        })->paginate(5);

        // Get all products not already attached to this page
        $availableProducts = Product::whereDoesntHave('pages', function($query) use ($page) {
            $query->where('page_id', $page->id);
        })->paginate(5);

        return view('admin.pages.management.manage', [
            'page' => $page->load(['banners', 'products']),
            'banners' => $page->banners()->paginate(5), // Page's current banners
            'availableBanners' => $availableBanners,   // Available banners to add
            'products' => $page->products()->with('category')->paginate(5), // Page's current products
            'availableProducts' => $availableProducts  // Available products to add
        ]);
    }

    /**
     * Attach a banner to a page
     */
    public function attachBanner(Request $request, Page $page): JsonResponse
    {
        $request->validate([
            'banner_id' => 'required|exists:banners,id',
            'sort' => 'integer|min:0',
        ]);

        // Check if banner is already attached
        if ($page->banners()->where('banner_id', $request->banner_id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'This banner is already attached to the page.'
            ], 422);
        }

        // Get the highest current sort order
        $maxSort = $page->banners()->max('sort') ?? 0;
        $sortOrder = $request->sort ?? ($maxSort + 1);

        // Attach the banner with sort order
        $page->banners()->attach($request->banner_id, [
            'sort' => $sortOrder
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Banner attached successfully',
            'banner' => Banner::find($request->banner_id),
            'sort' => $sortOrder
        ]);
    }

    /**
     * Detach a banner from a page
     */
    public function detachBanner(Page $page, Banner $banner): JsonResponse
    {
        if (!$page->banners()->where('banner_id', $banner->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'This banner is not attached to the page.'
            ], 404);
        }

        $page->banners()->detach($banner->id);

        return response()->json([
            'success' => true,
            'message' => 'Banner detached successfully'
        ]);
    }

    /**
     * Attach a product to a page
     */
    public function attachProduct(Request $request, Page $page): JsonResponse
    {
        if ($page->type_id != 2) {
            return response()->json([
                'success' => false,
                'message' => 'Products can only be added to product pages.'
            ], 403);
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'sort' => 'integer|min:0',
        ]);

        // Check if product is already attached
        if ($page->products()->where('product_id', $request->product_id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'This product is already attached to the page.'
            ], 422);
        }

        // Get the highest current sort order
        $maxSort = $page->products()->max('sort') ?? 0;
        $sortOrder = $request->sort ?? ($maxSort + 1);

        // Attach the product with sort order
        $page->products()->attach($request->product_id, [
            'sort' => $sortOrder
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Product attached successfully',
            'product' => Product::find($request->product_id),
            'sort' => $sortOrder
        ]);
    }

    /**
     * Detach a product from a page
     */
    public function detachProduct(Page $page, Product $product): JsonResponse
    {
        if (!$page->products()->where('product_id', $product->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'This product is not attached to the page.'
            ], 404);
        }

        $page->products()->detach($product->id);

        return response()->json([
            'success' => true,
            'message' => 'Product detached successfully'
        ]);
    }


}
