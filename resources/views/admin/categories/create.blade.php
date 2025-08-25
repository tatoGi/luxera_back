<x-admin.admin-layout>
    <div class="flex flex-wrap my-5 -mx-2 bg-slate-100 rounded-md">
        <div class="container mx-auto">
            <div class="card-box ">
                <form action="{{ route('categories.store', app()->getlocale()) }}" method="POST" enctype="multipart/form-data"
                    data-parsley-validate novalidate>
                    @csrf
                        <div class="w-1/2 relative bg-white mt-2 mb-2 rounded-md p-4 mx-auto">
                            <div class="rounded absolute   w-full mx-auto mt-4">
                                <!-- Tabs -->
                                
                                <div class="language-selector">
                                    <ul id="tabs" class="language-selector-list">
                                        @foreach (config('app.locales') as $locale)
                                            @if($locale === 'en') 
                                                <li class="language-selector-item mb-2   rounded-md bg-cyan-500 border-cyan-500">
                                            @elseif($locale === 'ka')
                                                <li class="language-selector-item bg-red-500   rounded-md border-red-600">
                                            @endif
                                                <a href="#locale-{{ $locale }}" class="language-selector-link">
                                                    <!-- You can use small icons here for each language -->
                                                    <img src="{{ $locale === 'en' ? asset('storage/flags/united-states.png') : asset('storage/flags/georgia.png') }}" alt="{{ $locale }}" class="language-icon">
                                                    <span class="language-name">{{ __('admin.locale_' . $locale) }}</span>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                    
                                </div>

                            </div>
                            <div class="flex s justify-center items-center flex-col mb-2">
                                <label for="category" class="text-sm font-medium text-gray-900 dark:text-gray-400">Main a
                                    Category</label>
                                <select id="category" name="parent_id"
                                    class="border w-full border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block  p-2.5  dark:border-gray-600 dark:placeholder-gray-400 dark:text-black dark:focus:ring-blue-500 dark:focus:border-blue-500 mt-2">
                                    <option value="">Choose a Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->title ?? '' }}</option>
                                    @endforeach
                                </select>
                            </div>

                           
                            <div id="tab-contents">
                                @foreach (config('app.locales') as $locale)
                                    <div id="locale-{{ $locale }}"
                                        class=" @if ($locale !== app()->getLocale()) hidden @endif p-4">
                                        <div class="flex flex-col w-full items-center justify-center mb-2">
                                            <label for="title_{{ $locale }}" class="text-sm font-medium"><span
                                                    class="text-red-500">*</span>Title ( {{ __('admin.locale_' . $locale) }})</label>
                                            <input type="text" name="{{ $locale }}[title]"
                                                id="title_{{ $locale }}"
                                                class="border w-full text-sm rounded-lg block p-2.5 @error('title_' . $locale) border-red-500 @enderror"
                                                placeholder="Title">
                                            @error('title_' . $locale)
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="flex w-fulls items-center justify-center flex-col mb-2">
                                            <label for="slug_{{ $locale }}" class="text-sm font-medium"><span
                                                    class="text-red-500">*</span>URL Keyword
                                                ( {{ __('admin.locale_' . $locale) }})</label>
                                            <input type="text" name="{{ $locale }}[slug]"
                                                id="slug_{{ $locale }}"
                                                class="border w-full text-sm rounded-lg block  p-2.5 @error('slug_' . $locale) border-red-500 @enderror"
                                                placeholder="URL Keyword">
                                            @error('slug_' . $locale)
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="flex w-full items-center justify-center flex-col mb-2">
                                            <label for="description_{{ $locale }}"
                                            class="text-sm font-medium text-gray-900 dark:text-gray-900"><span
                                                class="text-red-500">*</span>Description
                                            ( {{ __('admin.locale_' . $locale) }})</label>
                                           <div id="editor" class="editor">
                                           
                                        <textarea id="description_{{ $locale }}" name="{{ $locale }}[description]" 
                                            class="border  w-full ckeditor text-sm text-gray-900 rounded-lg border-gray-300 focus:ring-blue-500
                                             focus:border-blue-500 dark:border-gray-600 dark:placeholder-gray-400 dark:text-black
                                              dark:focus:ring-blue-500 dark:focus:border-blue-500 p-2.5 
                                             @error('description_' . $locale) border-red @enderror"
                                            placeholder="Description"></textarea>
                                        @error('description_' . $locale)
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                           </div>
                                           
                                            
                                        </div>
                                    </div>
                                @endforeach
                            </div>



                            <div class="flex w-full items-center justify-center flex-col mb-2 mt-2">
                                <div class="flex w-full  bg-grey-lighter">
                                    <label id="upload-label"
                                        class="w-64 flex flex-col items-center px-4 py-6 bg-white text-blue rounded-lg shadow-lg tracking-wide uppercase border border-blue cursor-pointer hover:bg-blue ">
                                        <svg class="w-8 h-8" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20">
                                            <path
                                                d="M16.88 9.1A4 4 0 0 1 16 17H5a5 5 0 0 1-1-9.9V7a3 3 0 0 1 4.52-2.59A4.98 4.98 0 0 1 17 8c0 .38-.04.74-.12 1.1zM11 11h3l-4-4-4 4h3v3h2v-3z" />
                                        </svg>
                                        <span id="upload-text" class="mt-2 text-base leading-normal">Select a file</span>
                                        <input id="upload-input" type='file' name="icon" class="hidden"
                                            onchange="uploadFile(this)" />
                                    </label>
                                    <div id="image-container"></div>
                                </div>
                            </div>

                            <div class="flex w-1/2   flex-col mb-2">
                                <label class="text-xl mr-2 mb-2 text-cyan-400 font-bold"> Active </label>

                                <label class="relative inline-flex cursor-pointer items-center">
                                    <input id="switch" type="checkbox" class="peer sr-only" name="active" value="1" checked />
                                    <label for="switch" class="hidden"></label>
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
                            @if ($errors->any())
                            <div class="mb-4">
                                <ul class="list-disc list-inside text-red-500">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                            <div class="submit text-right">
                                <button type="submit"
                                    class="middle none center mr-4 rounded-lg
                                                bg-blue-500 py-3 px-6 font-sans text-xs font-bold 
                                                uppercase text-white shadow-md shadow-blue-500/20 
                                                transition-all hover:shadow-lg hover:shadow-blue-500/40 
                                                focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] 
                                                active:shadow-none disabled:pointer-events-none disabled:opacity-50 
                                                disabled:shadow-none"
                                    data-ripple-light="true">
                                    Save
                                </button>
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
                    .create( document.querySelector( '#description_{{ $locale }}' ) )
                    .then( editor => {
                        console.log( editor );
                    } )
                    .catch( error => {
                        console.error( error );
                    } );
            @endforeach
        });
    </script>
</x-admin.admin-layout> 
