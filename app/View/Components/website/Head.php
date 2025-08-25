<?php

namespace App\View\Components\website;

use App\Models\Page;
use App\Models\Product;
use App\Models\ProductTranslation;
use Illuminate\Support\Facades\Request;
use Illuminate\View\Component;

class Head extends Component
{
    public $pages;
    public $ogImage;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->pages = Page::with('children.translation')
            ->whereHas('translation', function ($q) {
                $q->whereActive(true);
            })
            ->where('parent_id', null)
            ->orderBy('sort', 'asc')
            ->get();

        // Set the OG image URL based on the current page
        $this->ogImage = $this->getOGImage();
       
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.website.head', ['ogImage' => $this->ogImage]);
    }

    /**
     * Get the OG image URL based on the current page.
     *
     * @return string|null
     */
    private function getOGImage()
    {
        // Logic to determine the OG image URL based on the current page
        $currentPage = Request::route()->getName();
      
        // Check if the current page is the product show page
        if ($currentPage === 'single_product') {
            // Retrieve the product translation
            $url = Request::route('url');
            $product = Product::whereHas('translations', function ($query) use ($url) {
                $query->where('slug', $url);
            })->with('category', 'images')->first();
    
            if ($product) {
                // Retrieve the dynamic SEO data for the product
                $seoData = $product->getDynamicSEOData();
                $ogImage = $seoData->image;
            } else {
                $ogImage = null;
            }
            
            return $ogImage;
        }
    
        // If the current page is not a product show page, return null
        return null;
    }
    
}


