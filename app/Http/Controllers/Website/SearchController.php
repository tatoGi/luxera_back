<?php

namespace App\Http\Controllers\Website;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductTranslation;
use App\Models\Category;
use App\Models\CategoryTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SearchController extends Controller
{
    public static function search(Request $request)
    {
        // Validate the search query
        $validatedData = $request->validate(['que' => 'required']);
        $searchText = $validatedData['que'];
        
        // Language slugs
        $locale = app()->getLocale();
        $language_slugs = [$locale => "$locale/search?que=$searchText"];
        
        // Search in ProductTranslations
        $productTranslationIds = ProductTranslation::whereLocale($locale)
            ->where(function($query) use ($searchText) {
                $query->where('title', 'LIKE', "%{$searchText}%")
                    ->orWhere('description', 'LIKE', "%{$searchText}%")
                    ->orWhere('brand', 'LIKE', "%{$searchText}%")
                    ->orWhere('model', 'LIKE', "%{$searchText}%");
            })
            ->pluck('product_id')
            ->toArray();

        // Fetch Products with translations
        $products = Product::whereIn('id', $productTranslationIds)
            ->with('translations')
            ->paginate(settings('paginate'))
            ->appends(['que' => $searchText]);

        // Prepare product data
        $productData = $products->map(function($product) use ($locale) {
            $translation = $product->translate($locale);
            return [
                'product' => $product,
                'slug' => $translation->slug ?? '#',
                'title' => $translation->title,
                'desc' => Str::limit(strip_tags($translation->description)),
                'price' => $product->price,
                'images' => $product->images,
                'model' => $translation->model,
                'brand' => $translation->brand,
            ];
        });

        // Search in CategoryTranslations
        $categoryTranslationIds = CategoryTranslation::whereLocale($locale)
            ->where('title', 'LIKE', "%{$searchText}%")
            ->pluck('category_id')
            ->toArray();

        // Fetch Categories with translations
        $categories = Category::whereIn('id', $categoryTranslationIds)
            ->with('translations')
            ->get();

        // Prepare category data
        $categoryData = $categories->map(function($category) use ($locale) {
            $translation = $category->translate($locale);
            return [
                'slug' => $translation->slug ?? '#',
                'title' => $translation->title,
                'desc' => Str::limit(strip_tags($translation->description)),
            ];
        });

        return view('website.search.search_results', compact('products', 'productData', 'categoryData', 'language_slugs', 'searchText'));
    }
}
