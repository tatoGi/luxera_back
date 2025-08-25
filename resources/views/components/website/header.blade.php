<header class="li-header-4">
    <!-- Begin Header Top Area -->
    <div class="header-top">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="header-top-right">
                        <ul class="ht-menu">
                            <!-- Begin Setting Area -->
                            <li>
                                <div class="ht-setting-trigger">
                                    @auth('webuser')
                                        <span>{{ Auth::guard('webuser')->user()->first_name }}</span>
                                    @else
                                        <span>{{ __('messages.login_in') }}</span>
                                    @endauth
                                </div>
                        
                                @auth('webuser')
                                    <form id="logout-form" action="/{{ app()->getlocale() }}/logout" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                @endauth
                        
                                <div class="setting ht-setting">
                                    <ul class="ht-setting-list">
                                        @auth('webuser')
                                            <li><a href="/{{ app()->getlocale() }}/user_profile">{{ __('messages.my_account') }}</a></li>
                                            <li><a href="#">{{ __('messages.checkout') }}</a></li>
                                            <li>
                                                <a href="#"
                                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                   {{ __('messages.log_out') }}
                                                </a>
                                            </li>
                                        @else
                                            <li><a href="/{{ app()->getlocale() }}/login">{{ __('messages.sign_in') }}</a></li>
                                        @endauth
                                    </ul>
                                </div>
                            </li>
                        
                            <!-- Language Area -->
                            <li>
                                <ul class="ht-setting-list d-flex">
                                    @foreach (locales() as $locale => $data)
                                        @php
                                            $isActive = app()->getLocale() === $locale ? 'active' : '';
                                            $flagImg = $locale === 'en' ? '1.jpg' : 'georgia.png';
                                            $flagAlt = $locale === 'en' ? __('United States Flag') : __('Georgia Flag');
                                            $langName = $locale === 'en' ? __('messages.English') : __('messages.Georgia');
                                        @endphp
                                        <li class="{{ $isActive }}">
                                            <a href="{{ $data }}">
                                                <img src="{{ asset('website/images/menu/flag-icon/' . $flagImg) }}" alt="{{ $flagAlt }}"
                                                     style="width: 16px; height: 11px;">
                                                {{ $langName }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                            <!-- Language Area End Here -->
                        </ul>
                        
                    </div>
                </div>
                <!-- Header Top Right Area End Here -->
            </div>
        </div>
    </div>
    <!-- Header Top Area End Here -->
    <!-- Begin Header Middle Area -->
    <div class="header-middle pl-sm-0 pr-sm-0 pl-xs-0 pr-xs-0">
        <div class="container">
            <div class="row">
                <!-- Begin Header Logo Area -->

                <div class="col-lg-3 p-0">
                    <a href="/{{ app()->getLocale() }}/{{ isset($page) ? $page->translate(app()->getLocale())->slug : '' }}" class="logo_link">
                        <img src="{{ asset('storage/icons/logo-removebg-preview.png') }}" alt="logo" width="200" height="50">
                    </a>
                    
                </div>



                <!-- Header Logo Area End Here -->
                <!-- Begin Header Middle Right Area -->
                <div class="col-lg-9 p-4">
                    <!-- Begin Header Middle Searchbox Area -->
                    <form action="/{{ app()->getlocale() }}/search" class="hm-searchbox" method="get">
                       
                        <input type="text" name="que" placeholder="{{ __('messages.Enter_your_search_key') }} ...">
                        <button class="li-btn" type="submit"><i class="fa fa-search"></i></button>
                    </form>
                    <!-- Header Middle Searchbox Area End Here -->
                    <!-- Begin Header Middle Right Area -->
                    <div class="header-middle-right">
                        <ul class="hm-menu">
                            <!-- Begin Header Middle Wishlist Area -->
                            <li class="hm-wishlist">
                                <form id="wishlistForm" action="{{ url('/') }}/{{ app()->getlocale() }}/wishlist"
                                    method="post">
                                    @csrf
                                    <input type="hidden" name="_method" value="post">
                                </form>
                                <a href="#" onclick="submitWishlistForm(event)">
                                    <span class="wishlist-item-count">0</span>
                                    <i class="fa fa-heart-o" style="color: wheat"></i>
                                </a>
                            </li>



                            <!-- Header Middle Wishlist Area End Here -->
                            <!-- Begin Header Mini Cart Area -->
                            <!-- HTML for Mini Cart -->
                            <li class="hm-minicart">
                                <div class="hm-minicart-trigger">
                                    <span class="item-icon"></span>
                                    <span class="item-text">
                                       <span class="count-price">0.00</span> 
                                        <span class="cart-item-count">0</span>
                                    </span>
                                </div>
                                <span></span>
                                <div class="minicart">
                                    <!-- Mini Cart Product List -->
                                    <ul class="minicart-product-list">
                                        <!-- Products will be dynamically added here -->
                                    </ul>
                                    <!-- Mini Cart Total -->
                                    <p class="minicart-total">{{ __('messages.SUBTOTAL') }}: <span>0.00</span></p>
                                    <!-- Mini Cart Buttons -->
                                    <div class="minicart-button">
                                        <a href="/{{ app()->getlocale()}}/basket" class="li-button li-button-dark li-button-fullwidth li-button-sm">
                                            <span>{{ __('messages.View Full Cart') }}</span>
                                        </a>
                                        <a href="checkout.html" class="li-button li-button-fullwidth li-button-sm">
                                            <span>{{ __('messages.Checkout') }}</span>
                                        </a>
                                    </div>
                                </div>
                            </li>
                            

                            <!-- Header Mini Cart Area End Here -->
                        </ul>
                    </div>
                    <!-- Header Middle Right Area End Here -->
                </div>
                <!-- Header Middle Right Area End Here -->
            </div>
        </div>
    </div>
    <!-- Header Middle Area End Here -->
    <!-- Begin Header Bottom Area -->
    <div class="header-bottom header-sticky stick d-none d-lg-block d-xl-block">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <!-- Begin Header Bottom Menu Area -->
                    <div class="hb-menu">
                        <nav>

                            <ul>
                                @foreach ($pages as $page)
                                    <li class="megamenu-holder"><a
                                            href="/{{ app()->getlocale() }}/{{ $page->translate(app()->getLocale())->slug }}">{{ $page->translate(app()->getLocale())->title }}</a>
                                        @if ($page->children->count() > 0)
                                            <ul class="megamenu hb-megamenu">
                                                @foreach ($page->children as $children)
                                                    <li><a
                                                            href="{{ url($children->translate(app()->getLocale())->slug) }}">{{ $children->translate(app()->getLocale())->title }}</a>
                                                        @if ($children->children->count() > 0)
                                                            <ul>
                                                                @foreach ($children->children as $subchildren)
                                                                    <li><a
                                                                            href="{{ url($subchildren->translate(app()->getLocale())->slug) }}">{{ $subchildren->translate(app()->getLocale())->title }}</a>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endforeach

                            </ul>
                        </nav>
                    </div>
                    <!-- Header Bottom Menu Area End Here -->
                </div>
            </div>
        </div>
    </div>
    <!-- Header Bottom Area End Here -->
    <!-- Begin Mobile Menu Area -->
    <div class="mobile-menu-area mobile-menu-area-4 d-lg-none d-xl-none col-12">
        <div class="container">
            <div class="row">
                <div class="mobile-menu">
                </div>
            </div>
        </div>
    </div>
    <!-- Mobile Menu Area End Here -->
</header>
