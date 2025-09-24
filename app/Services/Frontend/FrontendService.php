<?php

namespace App\Services\Frontend;

use App\Models\Page;
use App\Models\Product;
use App\Models\Section;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Post;

class FrontendService
{
    public function getSectionData($slug)
    {
        $section = Page::with('translations')
            ->whereHas('translations', function ($query) use ($slug) {
                $query->where('slug', $slug);
            })
            ->firstOrFail();

        $products = Product::where('active', '1')
            ->with('category')
            ->with('translations')
            ->with('images')
            ->paginate(10);

        $categories = $products->pluck('category')->filter()->unique();
        $categoryIds = $products->pluck('category.id');
        $blogpage = Page::where('type_id', 2)->first();
        $blogPosts = Post::where('page_id', $blogpage->id)
            ->where('active', '1')
            ->orderBy('sort_order', 'asc')
            ->orderBy('published_at', 'desc')
            ->with('translations')
            ->with('attributes')
            ->paginate(10);

        return [
            'section' => $section,
            'categories' => $categories,
            'categoryIds' => $categoryIds,
            'products' => $products,
            'blogPosts' => $blogPosts,
            'slug' => $slug,
            'breadcrumbs' => $this->generateBreadcrumbs($section),
        ];
    }
    public function getNavigationData()
    {
        $locale = app()->getLocale();

        $pages = Page::whereHas('translations', function ($query) use ($locale) {
            $query->where('active', 1)
                  ->where('locale', $locale);
        })->with(['translations' => function ($query) use ($locale) {
            $query->select('page_id', 'title', 'slug', 'locale')
                  ->where('active', 1)
                  ->where('locale', $locale);
        }])
            ->get(['id']);

        return $pages->map(function ($page) use ($locale) {
            $t = $page->translations->firstWhere('locale', $locale);
            return [
                'title' => $t->title ?? null,
                'slug'  => $t->slug ?? null,
            ];
        })->filter(function ($item) {
            return !empty($item['title']) && !empty($item['slug']);
        })->values()->all();
    }
    public function getHomepageData()
    {
        $locale = app()->getLocale();

        $baseQuery = Product::query()
            ->where('active', 1)
            ->with(['translations' => function ($q) use ($locale) {
                $q->where('locale', $locale);
            }, 'images'])
            ->orderByDesc('sort_order')
            ->orderByDesc('created_at');

        $vipProducts = (clone $baseQuery)
            ->where('is_vip', 1)
            ->take(12)
            ->get();

        $featuredProducts = (clone $baseQuery)
            ->where('is_featured', 1)
            ->take(12)
            ->get();

        $bestSellingProducts = (clone $baseQuery)
            ->where('is_best_selling', 1)
            ->take(12)
            ->get();

        // Banners for homepage
        $mainBannerTypeId = data_get(config('bannerTypes.mainbanner'), 'id');
        $sellProductBannerTypeId = data_get(config('bannerTypes.sellProductBanner'), 'id');

        $bannerBase = Banner::query()
            ->with([
                'images',
                'translations' => function ($q) use ($locale) {
                    $q->where('locale', $locale);
                },
            ])
            ->orderByDesc('created_at');

        $mainBanners = $mainBannerTypeId
            ? (clone $bannerBase)->where('type_id', $mainBannerTypeId)->get()
            : collect();

        $sellProductBanners = $sellProductBannerTypeId
            ? (clone $bannerBase)->where('type_id', $sellProductBannerTypeId)->get()
            : collect();

        return [
            'vipProducts' => $vipProducts,
            'featuredProducts' => $featuredProducts,
            'bestSellingProducts' => $bestSellingProducts,
            'mainBanners' => $mainBanners,
            'sellProductBanners' => $sellProductBanners,
        ];
    }
    /**
     * Get all active pages with their translations
     *
     * @param int $postsPerPage Limit posts per page (default: 5)
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActivePages($postsPerPage = 5)
    {
        return Page::whereHas('translations', function ($query) {
            $query->where('active', 1);
        })->with(['translations' => function ($query) {
            $query->where('active', 1);
        }])
            ->with(['products' => function ($query) {
                $query->where('active', 1)
                    ->with(['images', 'translations']);
            }])
            ->with(['banners' => function ($query) {
                $query->with('images');
            }])
            ->with(['posts' => function ($query) use ($postsPerPage) {
                $query->where('active', 1)
                    ->orderBy('sort_order', 'asc')
                    ->orderBy('published_at', 'desc')
                    ->limit($postsPerPage) // Limit posts per page
                    ->with([
                        'translations',
                        'attributes',
                        'category.translations',
                    ]);
            }])
            ->get()
            ->map(function ($page) {
                // Transform posts to add post type information for homepage
                if ($page->type_id == 1 && $page->posts && $page->posts->count() > 0) { // Homepage
                    $page->posts->transform(function ($post) {
                        // Check if attributes exist and are loaded
                        if ($post->attributes && $post->attributes->count() > 0) {
                            // Get post_type attribute value
                            $postTypeAttr = $post->attributes->where('attribute_key', 'post_type')->first();
                            $postType = $postTypeAttr ? $postTypeAttr->attribute_value : 'join_us';

                            // Add post_type to the post object for frontend identification
                            $post->post_type = $postType;
                        } else {
                            // Default to join_us if no attributes found
                            $post->post_type = 'join_us';
                        }

                        return $post;
                    });
                }
                return $page;
            });
    }

    /**
     * Get all active pages with paginated posts
     *
     * @param int $page Current page number
     * @param int $postsPerPage Posts per page
     * @return array
     */
    public function getActivePagesWithPaginatedPosts($page = 1, $postsPerPage = 10)
    {
        $pages = Page::whereHas('translations', function ($query) {
            $query->where('active', 1);
        })->with(['translations' => function ($query) {
            $query->where('active', 1);
        }])
            ->with(['products' => function ($query) {
                $query->where('active', 1)
                    ->with(['images', 'translations']);
            }])
            ->with(['banners' => function ($query) {
                $query->with('images');
            }])
            ->get();

        // Add paginated posts to each page
        foreach ($pages as $page_item) {
            $posts = Post::where('page_id', $page_item->id)
                ->where('active', 1)
                ->orderBy('sort_order', 'asc')
                ->orderBy('published_at', 'desc')
                ->with([
                    'translations',
                    'attributes',
                    'category.translations',
                ])
                ->paginate($postsPerPage, ['*'], 'page', $page);

            $page_item->paginated_posts = [
                'data' => $posts->items(),
                'pagination' => [
                    'current_page' => $posts->currentPage(),
                    'last_page' => $posts->lastPage(),
                    'per_page' => $posts->perPage(),
                    'total' => $posts->total(),
                    'from' => $posts->firstItem(),
                    'to' => $posts->lastItem(),
                    'has_more_pages' => $posts->hasMorePages(),
                ]
            ];
        }

        return $pages;
    }
    public function getCategories()
    {
        return Category::with('products')->get();
    }

    /**
     * Get latest blog posts for homepage
     *
     * @param int $limit Number of posts to return (default: 10)
     * @return array
     */
    public function getLatestBlogPosts($limit = 10)
    {
        $blogPage = Page::where('type_id', 2)->first();
        
        if (!$blogPage) {
            return [
                'posts' => collect(),
                'total' => 0,
                'message' => 'Blog page not found'
            ];
        }

        $posts = Post::where('page_id', $blogPage->id)
            ->where('active', 1)
            ->orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->with([
                'translations',
                'attributes',
                'category.translations'
            ])
            ->limit($limit)
            ->get();

        return [
            'posts' => $posts,
            'total' => $posts->count(),
            'blog_page' => $blogPage->only(['id', 'type_id']),
            'message' => 'Latest blog posts retrieved successfully'
        ];
    }
    /**
     * Get a product by URL with related data
     *
     * @param string $url
     * @return array
     */
    public function getProductByUrl($url)
    {
        $product = Product::whereHas('translations', function ($query) use ($url) {
            $query->where('slug', $url);
        })->with('category', 'images')->first();

        if (!$product) {
            return ['error' => 'Product not found'];
        }

        $relatedProducts = $this->getRelatedProducts($product);

        return [
            'product' => $product,
            'seo' => $product->seo,
            'relatedProducts' => $relatedProducts
        ];
    }

    /**
     * Get related products
     *
     * @param Product $product
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getRelatedProducts($product)
    {
        return Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with('category')
            ->take(4)
            ->get();
    }

    /**
     * Generate breadcrumbs for a section
     *
     * @param mixed $section
     * @return array
     */
    protected function generateBreadcrumbs($section)
    {
        return [
            ['url' => '', 'label' => 'Home'],
            ['url' => $section->slug ?? '', 'label' => $section->title ?? '']
        ];
    }
}
