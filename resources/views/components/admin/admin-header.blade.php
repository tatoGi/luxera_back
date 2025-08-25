<div class="fixed w-full z-30 flex bg-white dark:bg-[#0F172A] p-2 items-center justify-center h-16 px-10">
    <div class="logo ml-12 dark:text-white transform ease-in-out duration-500 flex-none h-full flex items-center justify-center">
        {{ __('admin.Admin_Panel') }}
    </div>
    <!-- SPACER -->
    <div class="grow h-full flex items-center justify-center"></div>
    <div class="flex-none h-full text-center flex items-center justify-center">
        <div class="flex space-x-3 items-center px-3">
            <div class="flags flex">
                
                @foreach(locales() as $locale => $data)
                    @if($locale === 'en')
                        <!-- English Flag Icon -->
                        <a href="{{ $data }}" class="block h-4 mt-4 px-2 text-[#7f8890] hover:text-black transition-colors duration-300" title="{{ __('English') }}">
                           {{__('admin.english')}}
                        </a>
                    @elseif($locale === 'ka')
                        <!-- Georgian Flag Icon -->
                        <a href="{{ $data }}" class="block h-4 mt-4 px-2 text-[#7f8890] hover:text-black transition-colors duration-300" title="{{ __('Georgian') }}">
                          {{__('admin.georgian')}}
                        </a>
                    @endif
                @endforeach
            </div>
            <ul class="flex list-outside hover:list-inside">
                
                {{-- <li class="mr-12 inline-block">
                    <a href="#" class="block h-8 mt-4 px-15 text-[#7f8890] hover:text-black  transition-colors duration-300" title="{{ __('Blog Comments') }}" data-original-title="{{ __('Blog Comments') }}">
                        <i class="ico fas fa-comment-alt text-xl"></i>
                    </a>
                </li> --}}
                <div class="text-red-500 ml-4 mt-2 text-sm">
                    @php
                        // Get the count of contact messages
                        $contactMessageCount = \App\Models\Contact::count();
                    @endphp
                    {{ $contactMessageCount }}
                </div>
                <li class="w-8 mr-12 inline-block">
                    <a href="/{{ app()->getlocale() }}/admin/contact" class="block h-8 mt-4 px-15 text-[#7f8890] hover:text-black  transition-colors duration-300" title="{{ __('Contacts') }}">
                        <i class="ico fas fa-envelope text-xl"></i>
                    </a>
                </li>
            </ul>
            
            <div class="w-8 flex-none flex justify-center relative">
                <div class="w-8 h-8 flex">
                    
                </div>
                <!-- Dropdown menu -->
                <div id="mobileDropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10 hidden cursor-pointer">
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">{{ __('admin.Profile') }}</a>
                    <a href="/{{ app()->getlocale() }}/admin/logout" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">{{ __('admin.Logout') }}</a>
                </div>
            </div>
            
            <div class="md:block text-sm md:text-md text-black dark:text-white float-right cursor-pointer relative" onclick="toggleDropdown()">
                {{ Auth::user()->name }}
            </div>
            
            <script>
                function toggleDropdown() {
                    var dropdown = document.getElementById('mobileDropdown');
                    dropdown.classList.toggle('hidden');
                }
            </script>
            
        </div>
    </div>
</div>
