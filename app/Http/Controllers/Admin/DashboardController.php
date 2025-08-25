<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\WebUser;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): View
    {
        $products = Product::count();
        $activeProducts = Product::where('active', 1)->count();
        $inactiveProducts = Product::where('active', 0)->count();
        $categories = Category::count();
        $activeCategories = Category::where('active', 1)->count();
        $inactiveCategories = Category::where('active', 0)->count();
        $webusers = WebUser::count();

        return view('admin.analytics.index', [
            'products' => $products,
            'activeProducts' => $activeProducts,
            'inactiveProducts' => $inactiveProducts,
            'categories' => $categories,
            'activeCategories' => $activeCategories,
            'inactiveCategories' => $inactiveCategories,
            'webusers' => $webusers
        ]);
    }
}
