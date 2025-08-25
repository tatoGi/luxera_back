<x-admin.admin-layout>
    <div class="container mx-auto px-4 py-8">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">{{ __('admin.Manage_Page') }}: {{ $page->title }}</h1>
                <div class="flex gap-2">
                    <a href="{{ route('pages.index', [app()->getlocale()]) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                        {{ __('admin.Back_to_Pages') }}
                    </a>

                </div>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <div class="w-full">
                <div class="space-y-6">
                    <div class="bg-white rounded-lg shadow-sm">
                        @include('admin.banners.index', [
                            'page_id' => $page->id,
                            'banners' => $banners
                        ])
                    </div>
                    {{-- Products section temporarily disabled due to Bootstrap/Tailwind styling conflicts --}}
                    {{-- @if($page->type_id == 2 || $page->type_id == 1)
                    <div class="bg-white rounded-lg shadow-sm">
                        @include('admin.products.index', [
                            'page_id' => $page->id,
                            'products' => $products
                        ])
                    </div>
                    @endif --}}
                    
                    {{-- Page Products Section --}}
                    @if($page->type_id == 1 )
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-800">
                                    <i class="material-icons mr-2 text-blue-500">inventory_2</i>
                                    {{ __('admin.Page Products') }}
                                </h2>
                                <p class="text-sm text-gray-600 mt-1">
                                    {{ __('admin.Products specific to this page') }} - 
                                    <span class="font-medium">{{ $page->products()->count() }}</span> {{ __('admin.total products') }}
                                </p>
                            </div>
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.pages.products.index', ['locale' => app()->getLocale(), 'page' => $page->id]) }}"
                                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center transition-colors">
                                    <i class="material-icons text-sm mr-2">list</i>
                                    {{ __('admin.Manage Products') }}
                                </a>
                                <a href="{{ route('admin.pages.products.create', ['locale' => app()->getLocale(), 'page' => $page->id]) }}"
                                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center transition-colors">
                                    <i class="material-icons text-sm mr-2">add</i>
                                    {{ __('admin.Create Product') }}
                                </a>
                            </div>
                        </div>
                        
                        @php
                            $recentProducts = $page->products()->with('category')->orderBy('created_at', 'desc')->take(5)->get();
                        @endphp
                        
                        @if($recentProducts->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('admin.Product ID') }}
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('admin.Title') }}
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('admin.Category') }}
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('admin.Price') }}
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('admin.Status') }}
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('admin.Actions') }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($recentProducts as $product)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    {{ $product->product_identify_id }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $product->title }}</div>
                                                <div class="text-sm text-gray-500">{{ Str::limit($product->description, 40) }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($product->category)
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                                        {{ $product->category->title }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-400">{{ __('admin.No Category') }}</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                @if($product->price)
                                                    ${{ number_format((float)preg_replace('/[^0-9.]/', '', $product->price), 2) }}
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $product->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $product->active ? __('admin.Active') : __('admin.Inactive') }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('products.edit', [app()->getLocale(), $product->id]) }}?page_id={{ $page->id }}" 
                                                       class="text-blue-600 hover:text-blue-900 transition-colors">
                                                        <i class="material-icons text-sm">edit</i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="mt-4 text-center">
                                <a href="{{ route('admin.pages.products.index', ['locale' => app()->getLocale(), 'page' => $page->id]) }}"
                                   class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    {{ __('admin.View All Page Products') }} →
                                </a>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i class="material-icons text-4xl text-gray-400 mb-4">inventory_2</i>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('admin.No Products') }}</h3>
                                <p class="text-gray-500 mb-4">{{ __('admin.This page has no products attached yet.') }}</p>
                                <a href="{{ route('admin.pages.products.create', ['locale' => app()->getLocale(), 'page' => $page->id]) }}"
                                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg inline-flex items-center transition-colors">
                                    <i class="material-icons text-sm mr-2">add</i>
                                    {{ __('admin.Create First Product') }}
                                </a>
                            </div>
                        @endif
                    </div>
                    @endif
                    
                  
                    
                    {{-- Posts Section --}}
                    @if($page->supportsPost())
                    <div class="bg-white rounded-lg shadow-sm p-6 mt-6">
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-800">
                                    <i class="fas fa-file-alt mr-2 text-green-500"></i>
                                    {{ __('admin.Posts') }}
                                </h2>
                                <p class="text-sm text-gray-600 mt-1">
                                    {{ $pageTypeConfig['name'] ?? 'Posts' }} - 
                                    <span class="font-medium">{{ $page->posts()->count() }}</span> {{ __('admin.total posts') }}
                                </p>
                            </div>
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.pages.posts.index', ['locale' => app()->getLocale(), 'page' => $page->id]) }}"
                                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center transition-colors">
                                    <i class="fas fa-list mr-2"></i>
                                    {{ __('admin.Manage All') }}
                                </a>
                                <a href="{{ route('admin.pages.posts.create', ['locale' => app()->getLocale(), 'page' => $page->id]) }}"
                                   class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg flex items-center transition-colors">
                                    <i class="fas fa-plus mr-2"></i>
                                    {{ __('admin.Create Post') }}
                                </a>
                            </div>
                        </div>
                        
                        @php
                            $recentPosts = $page->posts()->orderBy('created_at', 'desc')->take(5)->get();
                            $pageTypeConfig = $page->getPageTypeConfig();
                        @endphp
                        
                        @if($recentPosts->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('admin.Title') }}
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('admin.Status') }}
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('admin.Sort Order') }}
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('admin.Created') }}
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('admin.Actions') }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($recentPosts as $post)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    @php
                                                        // Use Post model's dynamic attribute access
                                                        $title = $post->title ?? $post->question ?? 'Untitled';
                                                    @endphp
                                                    {{ Str::limit($title, 50) }}
                                                </div>
                                                @if($post->slug)
                                                    <div class="text-xs text-gray-500">{{ $post->slug }}</div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $post->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $post->active ? __('admin.Active') : __('admin.Inactive') }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $post->sort_order ?? 0 }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $post->created_at ? $post->created_at->format('M d, Y') : 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('admin.pages.posts.edit', ['locale' => app()->getLocale(), 'page' => $page->id, 'post' => $post->id]) }}"
                                                   class="text-indigo-600 hover:text-indigo-900 mr-3"
                                                   title="{{ __('admin.Edit') }}">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form method="POST" action="{{ route('admin.pages.posts.destroy', ['locale' => app()->getLocale(), 'page' => $page->id, 'post' => $post->id]) }}" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900" 
                                                            onclick="return confirm('{{ __('admin.Are you sure?') }}')"
                                                            title="{{ __('admin.Delete') }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="mt-4 text-center">
                                <a href="{{ route('admin.pages.posts.index', ['locale' => app()->getLocale(), 'page' => $page->id]) }}"
                                   class="text-green-600 hover:text-green-800 font-medium">
                                    {{ __('admin.View All Posts') }} ({{ $page->posts()->count() }})
                                </a>
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-500">
                                <i class="fas fa-file-alt text-4xl mb-4 text-gray-300"></i>
                                <p class="text-lg font-medium">{{ __('admin.No posts yet') }}</p>
                                <p class="text-sm mt-2">{{ __('admin.Create your first post to get started') }}</p>
                            </div>
                        @endif
                    </div>
                    @endif
                </div>

            </div>



    </div>
</x-admin.admin-layout>

@push('scripts')
<script>


    // Add banner to the list
    function addBanner() {
        const select = document.getElementById('new_banner_id');
        const bannerId = select.value;
        const bannerTitle = select.options[select.selectedIndex].text;
        const sortOrder = document.getElementById('banner_sort_order').value || 0;

        if (!bannerId) {
            alert('Please select a banner');
            return;
        }

        // Check if banner already exists in the list
        if (document.querySelector(`input[name="banners[${bannerId}][id]"]`)) {
            alert('This banner is already in the list');
            return;
        }

        // Create banner item
        const bannerHtml = `
            <div id="banner-${bannerId}" class="flex items-center justify-between p-2 bg-gray-50 rounded mb-2">
                <span>${bannerTitle} (Sort: ${sortOrder})</span>
                <input type="hidden" name="banners[${bannerId}][id]" value="${bannerId}">
                <input type="hidden" name="banners[${bannerId}][sort]" value="${sortOrder}" class="sort-input" data-id="banner-${bannerId}">
                <button type="button" onclick="removeItem('banner', ${bannerId})" class="text-red-600 hover:text-red-900">
                    {{ __('admin.Remove') }}
                </button>
            </div>
        `;

        // Add to the list
        document.getElementById('banner-items').insertAdjacentHTML('beforeend', bannerHtml);

        // Reset the form
        select.value = '';
        document.getElementById('banner_sort_order').value = '';
    }

    // Add product to the list
    function addProduct() {
        const select = document.getElementById('new_product_id');
        const productId = select.value;
        const productTitle = select.options[select.selectedIndex].text;
        const sortOrder = document.getElementById('product_sort_order').value || 0;

        if (!productId) {
            alert('Please select a product');
            return;
        }

        // Check if product already exists in the list
        if (document.querySelector(`input[name="products[${productId}][id]"]`)) {
            alert('This product is already in the list');
            return;
        }

        // Create product item
        const productHtml = `
            <div id="product-${productId}" class="flex items-center justify-between p-2 bg-gray-50 rounded mb-2">
                <span>${productTitle} (Sort: ${sortOrder})</span>
                <input type="hidden" name="products[${productId}][id]" value="${productId}">
                <input type="hidden" name="products[${productId}][sort]" value="${sortOrder}" class="sort-input" data-id="product-${productId}">
                <button type="button" onclick="removeItem('product', ${productId})" class="text-red-600 hover:text-red-900">
                    {{ __('admin.Remove') }}
                </button>
            </div>
        `;

        // Add to the list
        document.getElementById('product-items').insertAdjacentHTML('beforeend', productHtml);

        // Reset the form
        select.value = '';
        document.getElementById('product_sort_order').value = '';
    }

    // Remove item from the list
    function removeItem(type, id) {
        const element = document.getElementById(`${type}-${id}`);
        if (element) {
            element.remove();
        }
    }

    // Form submission handler
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('pageContentForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                // Prevent default form submission
                e.preventDefault();

                // Collect all banner data
                const banners = [];
                document.querySelectorAll('[name^="banners["]').forEach(input => {
                    const match = input.name.match(/banners\[(\d+)\]\[(\w+)\]/);
                    if (match) {
                        const id = match[1];
                        const field = match[2];
                        let banner = banners.find(b => b.id === id);
                        if (!banner) {
                            banner = { id };
                            banners.push(banner);
                        }
                        banner[field] = input.value;
                    }
                });

                // Collect all product data
                const products = [];
                document.querySelectorAll('[name^="products["]').forEach(input => {
                    const match = input.name.match(/products\[(\d+)\]\[(\w+)\]/);
                    if (match) {
                        const id = match[1];
                        const field = match[2];
                        let product = products.find(p => p.id === id);
                        if (!product) {
                            product = { id };
                            products.push(product);
                        }
                        product[field] = input.value;
                    }
                });

                // Update hidden inputs
                document.getElementById('bannersInput').value = JSON.stringify(banners);
                document.getElementById('productsInput').value = JSON.stringify(products);

                // Submit the form
                this.submit();
            });
        }
    });
</script>
@endpush
