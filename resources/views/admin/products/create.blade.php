<x-admin.admin-layout>

    <div class="flex flex-wrap my-5 -mx-2 bg-slate-100 rounded-md">

        <div class="container mx-auto">

            <div class="card-box ">

                <form
                    action="{{ isset($page) ? route('admin.pages.products.store', ['locale' => app()->getLocale(), 'page' => $page->id]) : route('products.store', app()->getlocale()) }}"
                    method="POST" enctype="multipart/form-data" data-parsley-validate novalidate>
                    @csrf
                    @if(isset($page))
                        <input type="hidden" name="page_id" value="{{ $page->id }}">
                    @endif
                    <div class="w-1/2 relative bg-white mt-2 mb-2 rounded-md p-4 mx-auto">

                        <div class="rounded absolute   w-full mx-auto mt-4">

                            <!-- Tabs -->

                            <div class="language-selector">

                                <ul id="tabs" class="language-selector-list">

                                    @foreach (config('app.locales') as $locale)
                                        @if ($locale === 'en')
                                            <li
                                                class="language-selector-item mb-2   rounded-md bg-cyan-500 border-cyan-500">
                                            @elseif($locale === 'ka')
                                            <li class="language-selector-item bg-red-500   rounded-md border-red-600">
                                        @endif

                                        <a href="#locale-{{ $locale }}" class="language-selector-link">

                                            <!-- You can use small icons here for each language -->

                                            <img src="{{ $locale === 'en' ? asset('storage/flags/united-states.png') : asset('storage/flags/georgia.png') }}"
                                                alt="{{ $locale }}" class="language-icon">

                                            <span class="language-name">{{ __('admin.locale_' . $locale) }}</span>

                                        </a>

                                        </li>
                                    @endforeach

                                </ul>



                            </div>



                        </div>

                        <div class="flex flex-col w-full mb-4">
                            <div class="flex justify-between gap-4">
                                <div class="w-full">
                                    <label for="category"
                                        class="block text-sm font-medium text-gray-900 dark:text-gray-400 mb-1">
                                        Category <span class="text-red-500">*</span>
                                    </label>
                                    <select id="category" name="category_id" required
                                        class="border w-full border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:border-gray-600 dark:placeholder-gray-400 dark:text-black dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        <option value="">Choose a Category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->title ?? '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                            </div>
                        </div>



                        <div id="tab-contents">

                            @foreach (config('app.locales') as $locale)
                                <div id="locale-{{ $locale }}"
                                    class=" @if ($locale !== app()->getLocale()) hidden @endif p-4">

                                    <div class="flex flex-col w-full items-center justify-center mb-2">

                                        <label for="title_{{ $locale }}" class="text-sm font-medium"><span
                                                class="text-red-500">*</span>Title (
                                            {{ __('admin.locale_' . $locale) }})</label>

                                        <input type="text" name="{{ $locale }}[title]"
                                            id="title_{{ $locale }}"
                                            class="border w-full text-sm rounded-lg block p-2.5 @error('title') border-red-500 @enderror"
                                            placeholder="Title">

                                        @error('title')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror

                                    </div>



                                    <div class="flex w-fulls items-center justify-center flex-col mb-2">

                                        <label for="slug_{{ $locale }}" class="text-sm font-medium"><span
                                                class="text-red-500">*</span>URL Keyword

                                            ({{ __('admin.locale_' . $locale) }})
                                        </label>

                                        <input type="text" name="{{ $locale }}[slug]"
                                            id="slug_{{ $locale }}"
                                            class="border w-full text-sm rounded-lg block  p-2.5 @error('slug') border-red-500 @enderror"
                                            placeholder="URL Keyword">

                                        @error('slug')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror

                                    </div>



                                    <div class="flex w-full items-center justify-center flex-col mb-2">
                                        <label for="slug_{{ $locale }}"
                                            class="text-sm font-medium text-gray-900 dark:text-gray-900">
                                            <span class="text-red-500">*</span>Slug
                                            ({{ __('admin.locale_' . $locale) }})
                                        </label>
                                        <input type="text" id="slug_{{ $locale }}"
                                            name="{{ $locale }}[slug]" value="{{ old($locale . '.slug') }}"
                                            class="border w-full text-sm rounded-lg block p-2.5 @error('slug') border-red-500 @enderror"
                                            placeholder="URL Keyword">
                                        @error('slug')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="flex w-full items-center justify-center flex-col mb-2">
                                        <label for="description_{{ $locale }}"
                                            class="text-sm font-medium text-gray-900 dark:text-gray-900">
                                            Description ({{ __('admin.locale_' . $locale) }})
                                        </label>
                                        <div id="editor" class="editor">
                                            <textarea id="description_{{ $locale }}" name="{{ $locale }}[description]"
                                                class="border w-full ckeditor text-sm text-gray-900 rounded-lg border-gray-300 focus:ring-blue-500
                                                        focus:border-blue-500 dark:border-gray-600 dark:placeholder-gray-400 dark:text-black
                                                        dark:focus:ring-blue-500 dark:focus:border-blue-500 p-2.5 @error('description') border-red @enderror"
                                                placeholder="Description">{{ old($locale . '.description') }}</textarea>
                                            @error('description')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="flex w-full items-center justify-center flex-col mb-2">
                                        <label for="brand_{{ $locale }}" class="text-sm font-medium">
                                            Brand ({{ __('admin.locale_' . $locale) }})
                                        </label>
                                        <input type="text" id="brand_{{ $locale }}"
                                            name="{{ $locale }}[brand]" value="{{ old($locale . '.brand') }}"
                                            class="border w-full text-sm rounded-lg block p-2.5 @error('style') border-red-500 @enderror"
                                            placeholder="e.g., Modern, Classic, Rustic">
                                        @error($locale . '.style')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="flex w-full items-center justify-center flex-col mb-2">
                                        <label for="location_{{ $locale }}" class="text-sm font-medium">
                                            Location ({{ __('admin.locale_' . $locale) }})
                                        </label>
                                        <input type="text" id="location_{{ $locale }}"
                                            name="{{ $locale }}[location]" value="{{ old($locale . '.location') }}"
                                            class="border w-full text-sm rounded-lg block p-2.5 @error($locale . '.location') border-red-500 @enderror"
                                            placeholder="e.g., Warehouse A, Shelf 12">
                                        @error($locale . '.location')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="flex w-full items-center justify-center flex-col mb-2">
                                        <label for="color_{{ $locale }}" class="text-sm font-medium">
                                            Color ({{ __('admin.locale_' . $locale) }})
                                        </label>
                                        <input type="text" id="color_{{ $locale }}"
                                            name="{{ $locale }}[color]" value="{{ old($locale . '.color') }}"
                                            class="border w-full text-sm rounded-lg block p-2.5 @error($locale . '.color') border-red-500 @enderror"
                                            placeholder="e.g., Red, Blue, Black">
                                        @error($locale . '.color')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                </div>
                            @endforeach

                        </div>

                        <!-- Product Identify ID -->
                        <div class="mb-4">
                            <label for="product_identify_id" class="block font-medium text-gray-700">
                                Product Identify ID
                                <span class="text-sm text-gray-500">(Auto-generated if empty)</span>
                            </label>
                            <input type="text" name="product_identify_id" id="product_identify_id"
                                class="border-gray-300 py-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border rounded-md shadow-sm p-2 @error('product_identify_id') border-red-500 @enderror"
                                value="{{ old('product_identify_id') }}" placeholder="e.g., PROD-ABC123">
                            @error('product_identify_id')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Features: Size (Color and Location are now translatable) -->
                        <div class="mb-4">
                            <div>
                                <label for="size" class="block font-medium text-gray-700">Size</label>
                                <input type="text" name="size" id="size"
                                    class="border-gray-300 py-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border rounded-md shadow-sm p-2 @error('size') border-red-500 @enderror"
                                    value="{{ old('size') }}" placeholder="e.g., S, M, L, XL">
                                @error('size')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Price and Sort Order -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="price" class="block font-medium text-gray-700">
                                    <span class="text-red-500">*</span>Price
                                </label>
                                <input type="text" name="price" id="price"
                                    class="border-gray-300 py-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border rounded-md shadow-sm p-2 @error('price') border-red-500 @enderror"
                                    value="{{ old('price') }}" placeholder="e.g., 99.99">
                                @error('price')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="sort_order" class="block font-medium text-gray-700">Sort Order</label>
                                <input type="number" name="sort_order" id="sort_order"
                                    class="border-gray-300 py-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border rounded-md shadow-sm p-2 @error('sort_order') border-red-500 @enderror"
                                    value="{{ old('sort_order', 0) }}" min="0">
                                @error('sort_order')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>




                        <div class="flex w-1/2 flex-col mb-2">
                            <label class="text-xl mr-2 mb-2 text-cyan-400 font-bold">Active</label>
                            <label class="relative inline-flex cursor-pointer items-center">
                                <input id="switch" type="checkbox" class="peer sr-only" name="active"
                                    value="1" {{ old('active', 1) ? 'checked' : '' }} />
                                <div
                                    class="peer h-6 w-12 rounded-full border bg-slate-200
                                    after:absolute after:left-[2px] after:top-0.5 after:h-5 after:w-5
                                    after:rounded-full after:border after:border-gray-300 after:bg-cyan-500
                                    after:transition-all after:content-['']
                                    peer-checked:bg-slate-800 peer-checked:after:translate-x-full
                                    peer-checked:after:border-white peer-focus:ring-green-300">
                                </div>
                            </label>
                        </div>

                        <div class="mb-4">

                            <label for="images" class="block font-medium text-gray-700">Images</label>

                            <input type="file" name="images[]" id="images" multiple
                                class="border-gray-300 py-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border rounded-md shadow-sm p-2">

                            @error('images')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror

                        </div>

                        @if ($errors->any())

                            <div class="mb-4">

                                <ul class="list-disc list-inside text-red-500">

                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach

                                </ul>

                            </div>

                        @endif

                        <div class="flex justify-between">

                            <div class="mb-4">

                                <button type="submit"
                                    class="bg-indigo-500 text-white py-2 px-4 rounded hover:bg-indigo-600">Create
                                    Product</button>

                            </div>

                            <div>

                                <a href="/{{ app()->getlocale() }}/admin/products"
                                    class="bg-indigo-500 text-white py-2 px-4 rounded hover:bg-indigo-600">Back</a>

                            </div>

                        </div>



                    </div>



                </form>

            </div>

        </div>

    </div>



    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>

    <script>
        $(document).ready(function() {

            @foreach (config('app.locales') as $locale)

                ClassicEditor

                    .create(document.querySelector('#description_{{ $locale }}'))

                    .then(editor => {

                        console.log(editor);

                    })

                    .catch(error => {

                        console.error(error);

                    });
            @endforeach

        });
    </script>

</x-admin.admin-layout>
