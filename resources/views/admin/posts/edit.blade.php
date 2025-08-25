<x-admin.admin-layout>
    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    {{ __('admin.Edit') }} {{ $pageTypeConfig['name'] ?? 'Post' }}
                </h1>
                <p class="text-gray-600 mt-1">{{ $page->title }}</p>
            </div>
            <a href="{{ route('admin.pages.posts.index', ['locale' => app()->getLocale(), 'page' => $page->id]) }}"
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                {{ __('admin.Back') }}
            </a>
        </div>

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif



        <form action="{{ route('admin.pages.posts.update', ['locale' => app()->getLocale(), 'page' => $page->id, 'post' => $post->id]) }}" 
              method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('admin.Basic Information') }}</h3>
                
                {{-- Post Type Selection for Homepage Posts --}}
                @if($page->type_id == 1)
                <div class="mb-4">
                    <label for="post_type" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-tags mr-2"></i>{{ __('admin.Post Type') }} <span class="text-red-500">*</span>
                    </label>
                    <select name="post_type" id="post_type" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">{{ __('admin.Select Post Type') }}</option>
                        <option value="join_us" {{ old('post_type', $existingAttributes['post_type'] ?? '') == 'join_us' ? 'selected' : '' }}>
                            {{ __('admin.Join Us Section') }}
                            <span class="text-gray-500">- გამოიმუშავე დამატებითი</span>
                        </option>
                        <option value="rental_steps" {{ old('post_type', $existingAttributes['post_type'] ?? '') == 'rental_steps' ? 'selected' : '' }}>
                            {{ __('admin.Rental Steps') }}
                            <span class="text-gray-500">- იქირავე შენთვის სასურველი</span>
                        </option>
                    </select>
                    <p class="text-sm text-gray-600 mt-1">
                        <i class="fas fa-info-circle mr-1"></i>
                        {{ __('admin.Select the type of homepage post to show relevant fields') }}
                    </p>
                </div>
                @endif

                {{-- Category Selection for Blog Posts --}}
                @if($page->type_id == 2)
                <div class="mb-4">
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-folder mr-2"></i>{{ __('admin.Category') }}
                    </label>
                    <select name="category_id" id="category_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">{{ __('admin.Select Category') }} ({{ __('admin.Optional') }})</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $post->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->title }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-sm text-gray-600 mt-1">
                        <i class="fas fa-info-circle mr-1"></i>
                        {{ __('admin.Select a category to organize your blog post') }}
                    </p>
                </div>
                @endif
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="active" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('admin.Status') }}
                        </label>
                        <select name="active" id="active" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <option value="1" {{ old('active', $post->active) == '1' ? 'selected' : '' }}>{{ __('admin.Active') }}</option>
                            <option value="0" {{ old('active', $post->active) == '0' ? 'selected' : '' }}>{{ __('admin.Inactive') }}</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('admin.Sort Order') }}
                        </label>
                        <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $post->sort_order) }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>
                    
                    <div>
                        <label for="published_at" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('admin.Publish Date') }}
                        </label>
                        <input type="datetime-local" name="published_at" id="published_at" 
                               value="{{ old('published_at', $post->published_at ? $post->published_at->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>
                </div>
            </div>

            {{-- Translatable Attributes --}}
            @if(!empty($translatableAttributes))
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('admin.Content') }}</h3>
                
                <!-- Language Tabs -->
                <div class="border-b border-gray-200 mb-6" id="language-tabs-container">
                    <nav class="-mb-px flex space-x-8" id="language-tabs-nav">
                        @foreach(config('app.locales') as $locale)
                            <button type="button"
                               class="post-language-tab py-2 px-1 border-b-2 font-medium text-sm {{ $loop->first ? 'border-green-500 text-green-600 bg-green-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                               data-locale="{{ $locale }}"
                               id="lang-tab-{{ $locale }}">
                                {{ __('admin.locale_' . $locale) }}
                                <img src="{{ $locale === 'en' ? asset('storage/flags/united-states.png') : asset('storage/flags/georgia.png') }}" 
                                     alt="{{ $locale }}" class="inline w-4 h-4 ml-1">
                            </button>
                        @endforeach
                    </nav>
                </div>

                @foreach(config('app.locales') as $locale)
                    <div class="post-language-content {{ !$loop->first ? 'hidden' : '' }}" data-locale="{{ $locale }}" id="lang-content-{{ $locale }}">
                        <div class="grid grid-cols-1 gap-4">
                            @foreach($translatableAttributes as $key => $config)
                            <div class="field-group" 
                                 data-show-for-types="{{ isset($config['show_for_types']) ? json_encode($config['show_for_types']) : '[]' }}"
                                 style="{{ isset($config['show_for_types']) && !in_array($existingAttributes['post_type'] ?? '', $config['show_for_types'] ?? []) ? 'display: none;' : '' }}">
                                <label for="{{ $locale }}_{{ $key }}" class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ $config['label'] ?? ucfirst($key) }}
                                    @if($config['required'] ?? false)
                                        <span class="text-red-500">*</span>
                                    @endif
                                </label>
                                    
                                    @if($config['type'] === 'text')
                                        <input type="text" 
                                               name="{{ $locale }}[{{ $key }}]" 
                                               id="{{ $locale }}_{{ $key }}"
                                               value="{{ old($locale . '.' . $key, $existingAttributes[$locale][$key] ?? '') }}"
                                               placeholder="{{ $config['placeholder'] ?? '' }}"
                                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                    
                                    @elseif($config['type'] === 'textarea')
                                        <textarea name="{{ $locale }}[{{ $key }}]" 
                                                  id="{{ $locale }}_{{ $key }}"
                                                  rows="3"
                                                  placeholder="{{ $config['placeholder'] ?? '' }}"
                                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">{{ old($locale . '.' . $key, $existingAttributes[$locale][$key] ?? '') }}</textarea>
                                    
                                    @elseif($config['type'] === 'editor')
                                        <textarea name="{{ $locale }}[{{ $key }}]" 
                                                  id="{{ $locale }}_{{ $key }}"
                                                  rows="6"
                                                  placeholder="{{ $config['placeholder'] ?? '' }}"
                                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">{{ old($locale . '.' . $key, $existingAttributes[$locale][$key] ?? '') }}</textarea>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
            @endif

            {{-- Non-translatable Attributes --}}
            @if(!empty($nonTranslatableAttributes))
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('admin.Additional Information') }}</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($nonTranslatableAttributes as $key => $config)
                        <div class="{{ $config['type'] === 'editor' ? 'md:col-span-2' : '' }} field-group" 
                             data-show-for-types="{{ isset($config['show_for_types']) ? json_encode($config['show_for_types']) : '[]' }}"
                             style="{{ isset($config['show_for_types']) && !in_array($existingAttributes['post_type'] ?? '', $config['show_for_types'] ?? []) ? 'display: none;' : '' }}">
                            <label for="{{ $key }}" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ $config['label'] ?? ucfirst($key) }}
                                @if($config['required'] ?? false)
                                    <span class="text-red-500">*</span>
                                @endif
                            </label>
                            
                            @if($config['type'] === 'text')
                                <input type="text" 
                                       name="{{ $key }}" 
                                       id="{{ $key }}"
                                       value="{{ old($key, $existingAttributes[$key] ?? '') }}"
                                       placeholder="{{ $config['placeholder'] ?? '' }}"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            
                            @elseif($config['type'] === 'number')
                                <input type="number" 
                                       name="{{ $key }}" 
                                       id="{{ $key }}"
                                       value="{{ old($key, $existingAttributes[$key] ?? '') }}"
                                       placeholder="{{ $config['placeholder'] ?? '' }}"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            
                            @elseif($config['type'] === 'textarea')
                                <textarea name="{{ $key }}" 
                                          id="{{ $key }}"
                                          rows="3"
                                          placeholder="{{ $config['placeholder'] ?? '' }}"
                                          class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">{{ old($key, $existingAttributes[$key] ?? '') }}</textarea>
                            
                            @elseif($config['type'] === 'select')
                                <select name="{{ $key }}" 
                                        id="{{ $key }}"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                    @foreach($config['options'] ?? [] as $value => $label)
                                        <option value="{{ $value }}" {{ old($key, $existingAttributes[$key] ?? '') == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            
                            @elseif($config['type'] === 'image')
                                <div class="space-y-2">
                                    @if($existingAttributes[$key] ?? false)
                                        <div class="mb-2 current-image" data-attr-key="{{ $key }}">
                                            <img src="{{ asset('storage/' . $existingAttributes[$key]) }}" 
                                                 alt="Current {{ $key }}" 
                                                 class="w-32 h-32 object-cover rounded-lg border">
                                            <p class="text-sm text-gray-600 mt-1">Current image</p>
                                            <input type="hidden" name="remove_{{ $key }}" value="0">
                                            <button type="button" 
                                                    class="mt-2 inline-flex items-center px-3 py-1.5 remove-image text-sm font-medium rounded-md bg-red-600 text-white hover:bg-red-700 btn-remove-image focus:outline-none focus:ring-2 focus:ring-red-500"
                                                    data-attr-key="{{ $key }}">
                                                Remove image
                                            </button>
                                            <p class="text-xs text-red-600 mt-1 hidden removal-note">Marked for removal. Save to apply.</p>
                                        </div>
                                    @endif
                                    <input type="file" 
                                           name="{{ $key }}" 
                                           id="{{ $key }}"
                                           accept="{{ $config['accept'] ?? 'image/*' }}"
                                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                    @if($existingAttributes[$key] ?? false)
                                        <p class="text-sm text-gray-600">Upload a new image to replace the current one</p>
                                    @endif
                                </div>
                            @elseif($config['type'] === 'datetime-local')
                                <input type="datetime-local" 
                                       name="{{ $key }}" 
                                       id="{{ $key }}"
                                       value="{{ old($key, $existingAttributes[$key] ?? '') }}"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.pages.posts.index', ['locale' => app()->getLocale(), 'page' => $page->id]) }}"
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-colors">
                    {{ __('admin.Cancel') }}
                </a>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition-colors">
                    <i class="fas fa-save mr-2"></i>
                    {{ __('admin.Update Post') }}
                </button>
            </div>
        </form>
    </div>

    <script>
        // Toggle fields based on post type selection
        function togglePostTypeFields() {
            const postTypeSelect = document.getElementById('post_type');
            if (!postTypeSelect) return;
            
            const selectedType = postTypeSelect.value;
            console.log('Selected post type:', selectedType);
            
            // Get all field groups (both translatable and non-translatable)
            const fieldGroups = document.querySelectorAll('.field-group');
            
            fieldGroups.forEach(group => {
                const showForTypes = group.getAttribute('data-show-for-types');
                
                if (showForTypes) {
                    try {
                        const types = JSON.parse(showForTypes);
                        // If no post type is selected (empty string or "0"), hide fields with restrictions
                        // If a post type is selected, show only fields that include that type
                        if (!selectedType || selectedType === '' || selectedType === '0') {
                            group.style.display = 'none';
                            console.log('Hiding field group, no post type selected');
                        } else if (types.includes(selectedType)) {
                            group.style.display = '';
                            console.log('Showing field group for type:', selectedType);
                        } else {
                            group.style.display = 'none';
                            console.log('Hiding field group, not for type:', selectedType);
                        }
                    } catch (e) {
                        console.error('Error parsing show_for_types:', e);
                        group.style.display = '';
                    }
                } else {
                    // Show fields without restrictions
                    group.style.display = '';
                }
            });
        }

        function switchPostLanguageTab(locale) {
            console.log('=== SWITCHING POST LANGUAGE TAB ===');
            console.log('Target locale:', locale);
            console.log('Available tabs:', document.querySelectorAll('.post-language-tab'));
            console.log('Available content:', document.querySelectorAll('.post-language-content'));
            
            // Remove active classes from all post language tabs
            const allTabs = document.querySelectorAll('.post-language-tab');
            console.log('Found tabs count:', allTabs.length);
            
            allTabs.forEach((tab, index) => {
                console.log(`Tab ${index}: locale="${tab.dataset.locale}", id="${tab.id}"`);
                tab.classList.remove('border-green-500', 'text-green-600', 'bg-green-50');
                tab.classList.add('border-transparent', 'text-gray-500');
            });
            
            // Add active classes to selected tab
            const activeTab = document.getElementById(`lang-tab-${locale}`);
            console.log('Looking for tab with ID:', `lang-tab-${locale}`);
            console.log('Found active tab:', activeTab);
            
            if (activeTab) {
                activeTab.classList.add('border-green-500', 'text-green-600', 'bg-green-50');
                activeTab.classList.remove('border-transparent', 'text-gray-500');
                console.log('✓ Activated post tab for locale:', locale);
            } else {
                console.error('✗ Post tab not found for locale:', locale);
                console.log('Available tab IDs:', Array.from(allTabs).map(t => t.id));
            }
            
            // Hide all post language content sections
            const allContent = document.querySelectorAll('.post-language-content');
            console.log('Found content sections count:', allContent.length);
            
            allContent.forEach((content, index) => {
                console.log(`Content ${index}: locale="${content.dataset.locale}", id="${content.id}"`);
                content.classList.add('hidden');
            });
            
            // Show selected content section
            const activeContent = document.getElementById(`lang-content-${locale}`);
            console.log('Looking for content with ID:', `lang-content-${locale}`);
            console.log('Found active content:', activeContent);
            
            if (activeContent) {
                activeContent.classList.remove('hidden');
                console.log('✓ Showed post content for locale:', locale);
            } else {
                console.error('✗ Post content not found for locale:', locale);
                console.log('Available content IDs:', Array.from(allContent).map(c => c.id));
            }
            
            console.log('=== END SWITCH ===');
        }

        // Initialize language tabs immediately when script loads
        function initializeLanguageTabs() {
            console.log('🚀 INITIALIZING LANGUAGE TABS 🚀');
            
            // Wait a bit for DOM to be ready
            setTimeout(() => {
                console.log('DOM should be ready now, setting up tabs...');
                
                // Post language tab switching - isolated from other tab systems
                const postLanguageTabs = document.querySelectorAll('.post-language-tab');
                console.log('Found post language tabs:', postLanguageTabs.length);
                console.log('Tabs found:', postLanguageTabs);
                
                if (postLanguageTabs.length === 0) {
                    console.error('❌ NO LANGUAGE TABS FOUND!');
                    console.log('Available elements with class containing "tab":', document.querySelectorAll('[class*="tab"]'));
                    return;
                }
                
                postLanguageTabs.forEach((tab, index) => {
                    console.log(`Setting up post tab ${index}:`, tab.dataset.locale, 'ID:', tab.id);
                    
                    // Remove any existing event listeners by cloning the element
                    const newTab = tab.cloneNode(true);
                    tab.parentNode.replaceChild(newTab, tab);
                    
                    // Add click event listener to the new element
                    newTab.addEventListener('click', function(e) {
                        console.log('🔥 CLICK EVENT FIRED! 🔥');
                        console.log('Clicked tab locale:', this.dataset.locale);
                        console.log('Clicked tab ID:', this.id);
                        
                        e.preventDefault();
                        e.stopPropagation();
                        
                        switchPostLanguageTab(this.dataset.locale);
                    });
                    
                    // Make sure it's clickable
                    newTab.style.cursor = 'pointer';
                    newTab.style.userSelect = 'none';
                    
                    console.log('✅ Tab setup complete for:', newTab.dataset.locale);
                });
                
                // Initialize first post language tab as active
                const firstPostTab = document.querySelector('.post-language-tab');
                if (firstPostTab) {
                    console.log('Initializing first post tab:', firstPostTab.dataset.locale);
                    switchPostLanguageTab(firstPostTab.dataset.locale);
                } else {
                    console.error('No post language tabs found after setup!');
                }
                
            }, 500); // Increased delay to ensure DOM is fully ready
        }

        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM Content Loaded - Initializing components');
            
            // Initialize post type field toggling
            const postTypeSelect = document.getElementById('post_type');
            if (postTypeSelect) {
                postTypeSelect.addEventListener('change', togglePostTypeFields);
                setTimeout(togglePostTypeFields, 100);
            }
            
            // Initialize language tabs with multiple fallback methods
            initializeLanguageTabs();
            
            // Fallback method 1: Try again after a longer delay
            setTimeout(() => {
                console.log('🔄 FALLBACK 1: Trying to initialize tabs again...');
                if (document.querySelectorAll('.post-language-tab').length > 0) {
                    initializeLanguageTabs();
                }
            }, 1000);
            
            // Fallback method 2: Manual click handler setup
            setTimeout(() => {
                console.log('🔄 FALLBACK 2: Setting up manual click handlers...');
                const tabs = document.querySelectorAll('.post-language-tab');
                tabs.forEach(tab => {
                    if (!tab.hasAttribute('data-click-setup')) {
                        tab.setAttribute('data-click-setup', 'true');
                        tab.onclick = function(e) {
                            console.log('📱 MANUAL CLICK HANDLER FIRED!');
                            e.preventDefault();
                            switchPostLanguageTab(this.dataset.locale);
                        };
                    }
                });
            }, 1500);
            
            // Using plain textareas now; no TinyMCE initialization

            // Handle image remove buttons in post edit form
            document.querySelectorAll('.btn-remove-image').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var key = this.getAttribute('data-attr-key');
                    var container = this.closest('.current-image');
                    if (!container) return;
                    var hidden = container.querySelector('input[type="hidden"][name="remove_' + key + '"]');
                    if (hidden) {
                        hidden.value = '1';
                    }
                    var img = container.querySelector('img');
                    if (img) {
                        img.style.display = 'none';
                    }
                    var note = container.querySelector('.removal-note');
                    if (note) {
                        note.classList.remove('hidden');
                    }
                    // Optionally disable file input if you want to force removal only
                    // Find sibling file input in the same field block
                    var fieldBlock = container.parentElement;
                    var fileInput = fieldBlock.querySelector('input[type="file"][name="' + key + '"]');
                    if (fileInput) {
                        fileInput.value = '';
                    }
                });
            });
        });
    </script>
</x-admin.admin-layout>
