<?php

namespace App\Http\View\Composers;

use App\Models\Page;
use App\Models\Product;
use Illuminate\Support\Facades\Request;
use Illuminate\View\View;

class WebsiteComposer
{
    public function compose(View $view)
    {
        $page = Page::with('children.translations')
            ->whereHas('translations', function ($q) {
                $q->whereActive(true);
            })
            ->with('translations')
            ->with('options.images')
            ->where('parent_id', null)
            ->where('type_id', '1')
            ->orderBy('sort', 'asc')
            ->first();

            $product = null;
            if (Request::route()->getName() === 'single_product') {
                $url = Request::route('url');
                $product = Product::whereHas('translations', function ($query) use ($url) {
                    $query->where('slug', $url);
                })->with('category', 'images')->first();
            }

        $pages = Page::with('children.translation')
            ->whereHas('translation', function ($q) {
                $q->whereActive(true);
            })
            ->where('parent_id', null)
            ->orderBy('sort', 'asc')
            ->get();
            $seo = $page->seo ?? '';
           
        $view->with([
            'page' => $page,
            'pages' => $pages,
            'seo' => $seo,
            'product' => $product
        ]);
    }
}
