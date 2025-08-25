<div class="flex flex-wrap my-5 -mx-2 bg-slate-100 rounded-md">

    <div class="container mx-auto">

        <div class="card-box ">
            <form method="POST" action="{{ route('settings.update', app()->getLocale()) }}" enctype="multipart/form-data">
                @csrf
                @method('POST')
            <div class="relative bg-white mt-2 mb-2 rounded-md p-4 mx-auto">
                {{-- <div class="rounded absolute   w-full mx-auto mt-4">

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
                <div class="tab-content" id="tab-contents">
                    @foreach (config('app.locales') as $locale)
                        <div role="tabpanel" class="tab-pane @if ($locale == app()->getLocale()) block @else hidden @endif"
                             id="locale-{{ $locale }}">
                            @foreach (settingTransAttrs($settings) as $key => $field)
                                <div class="mb-4">
                                    <label for="{{ $key }}" class="block text-sm font-medium text-gray-700">{{ trans('admin.' . $key) }}</label>
                                 
                                    @if (isset($field['type']) && $field['type'] == 'textarea')
                                        <textarea name="translatables[{{ $key }}][{{ $locale }}]"
                                                  class="mt-1 block w-full h-32 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-indigo-300 ckeditor">{{ $field['value'][$locale] ?? null }}</textarea>
                                    @elseif(isset($field['type']) && $field['type'] == 'text')
                                        <input type="text" name="translatables[{{ $key }}][{{ $locale }}]"
                                               class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-indigo-300"
                                               value="{{ $field['value'][$locale] }}">
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>

                <br> --}}

                @foreach (settingNonTransAttrs($settings) as $key1 => $field1)
               
                    <div class="mb-4">
                        <label for="{{ $key1 }}" class="block text-sm font-medium text-gray-700">{{ trans('admin.' . $key1) }}</label>
                       
                       
        @if (isset($field1['type']) && $field1['type'] === 'text')
        <input type="text" name="nonTranslatables[{{ $key1 }}]"
               class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-indigo-300"
               value="{{ $field1['value'] ?? '' }}">
    @endif
                    </div>
                    
                @endforeach

                <div class="text-right">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                        {{ trans('admin.save') }}
                    </button>
                </div>
            </form>

        </div>
    </div>
    </div>
</div>
