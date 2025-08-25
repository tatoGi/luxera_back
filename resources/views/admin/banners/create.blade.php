<x-admin.admin-layout> 


    <div class="flex flex-wrap my-5 -mx-2">

        <div class="w-1/2 bg-white mt-2 mb-2 rounded-md p-4 mx-auto">

            <h1 class="text-center mb-5">Create Banner</h1>

            <form action="{{ route('banners.store',['page_id'=>$page_id, app()->getlocale()]) }}" method="POST" class="max-w-md mx-auto" enctype="multipart/form-data">
               
              
                @csrf

                <!-- category_id -->

                <div class="rounded  w-full mx-auto mt-4">

                    <!-- Tabs -->

                    

                    <div class="language-selector">

                        <ul id="tabs" class="language-selector-list">

                            @foreach (config('app.locales') as $locale)

                                @if($locale === 'en') 

                                    <li class="language-selector-item border-cyan-500">

                                @elseif($locale === 'ka')

                                    <li class="language-selector-item border-red-600">

                                @endif

                                    <a href="#locale-{{ $locale }}" class="language-selector-link">

                                        <!-- You can use small icons here for each language -->

                                     

                                        <span class="language-name">{{ __('admin.locale_' . $locale) }}</span>

                                    </a>

                                </li>

                            @endforeach

                        </ul>

                        

                    </div>

    

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

                                    class="text-sm font-medium text-gray-900 dark:text-gray-400"><span

                                        class="text-red-500">*</span>Description

                                    ( {{ __('admin.locale_' . $locale) }})</label>

                                <textarea id="description_{{ $locale }}" name="{{ $locale }}[desc]" rows="4"

                                    class="border w-full text-sm text-gray-900 rounded-lg border-gray-300 focus:ring-blue-500

                                     focus:border-blue-500 dark:border-gray-600 dark:placeholder-gray-400 dark:text-black

                                      dark:focus:ring-blue-500 dark:focus:border-blue-500 p-2.5 

                                     @error('description_' . $locale) border-red @enderror"

                                    placeholder="Description"></textarea>

                                @error('description_' . $locale)

                                    <span class="text-red-500 text-sm">{{ $message }}</span>

                                @enderror

                            </div>

                        </div>

                    @endforeach

                </div>

                <div class="flex flex-col w-full items-center justify-center mb-2">

                    <label for="title_{{ $locale }}" class="text-sm font-medium"><span

                            class="text-red-500">*</span>{{ __('admin.type') }}</label>

                            <select class="border w-full text-sm rounded-lg block  p-2.5 @error('type') danger @enderror " name="type_id" id="typeselect">

                                @foreach ($bannerTypes as $key => $type)

                                <option value="{{ $type['id'] }}" id="typeoption">{{ __('bannerTypes.'.$key) }}</option>

                                @endforeach

                            </select>

                </div>

                

                <!-- sort -->

    

                <!-- active -->

                <div class="flex flex-col mb-2">

                    <label class="text-xl mr-2 mb-2 text-cyan-400 font-bold"> Active </label>

                   

                    <label class="relative inline-flex cursor-pointer items-center">

                        <input id="switch" type="checkbox" class="peer sr-only" value="1" checked />

                        <label for="switch" class="hidden"></label>

                        <div class="peer h-6 w-12 rounded-full border bg-slate-200 

                        after:absolute after:left-[2px] after:top-0.5 after:h-5 after:w-5 

                        after:rounded-full after:border after:border-gray-300 after:bg-cyan-500

                        after:transition-all after:content-['']

                         peer-checked:bg-slate-800 peer-checked:after:translate-x-full

                          peer-checked:after:border-white peer-focus:ring-green-300">

                        </div>

                      </label>

                </div>

    

                <!-- Banner Images -->
                <div class="mb-4">
                    <label for="images" class="block font-medium text-gray-700">Banner Images</label>
                    <input type="file" name="images[]" id="images" multiple accept="image/*" class="border-gray-300 py-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border rounded-md shadow-sm p-2">
                    <p class="text-sm text-gray-500 mt-1">You can select multiple images for the banner</p>
                    @error('images.*')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex justify-between">

                    <div class="mb-4">

                        <button type="submit" class="bg-indigo-500 text-white py-2 px-4 rounded hover:bg-indigo-600">Create Banner</button>

                    </div>

                    <div>

                        <a href="/{{app()->getlocale()}}/admin/banners" class="bg-indigo-500 text-white py-2 px-4 rounded hover:bg-indigo-600">Back</a>

                    </div>

                </div>

               <input type="hidden" name="author_id" value="{{ auth()->user()->id }}">

            </form>

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
