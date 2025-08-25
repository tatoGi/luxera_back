 <!-- Begin Footer Area -->
 <div class="footer">

    <!-- Footer Static Top Area End Here -->
    <!-- Begin Footer Static Middle Area -->
    <div class="footer-static-middle">
        <div class="container">
            <div class="footer-logo-wrap pt-50 pb-35">
                <div class="row">
                    <!-- Begin Footer Logo Area -->
                    <div class="col-lg-4 col-md-6">
                        <div class="footer-logo">
                           
                        </div>
                        <ul class="des">
                            {{-- <li>
                                <span>Address: </span>
                                6688Princess Road, London, Greater London BAS 23JK, UK
                            </li> --}}
                            <li>
                                <span>{{ __('messages.Phone') }}: </span>
                                <a href="tel:{{ settings('footer_email') }}">{{ settings('footer_phone') }}</a>
                            </li>
                            <li>
                                <span>{{ __('messages.Email') }}: </span>
                                <a href="mailto://{{ settings('email_for_footer') }}">{{ settings('email_for_footer') }}</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-lg-4">
                        <div class="footer-block">
                            <h3 class="footer-block-title">{{ __('messages.Follow Us') }}</h3>
                            <ul class="social-link">
                                {{-- <li class="twitter">
                                    <a href="https://twitter.com/" data-toggle="tooltip" target="_blank" title="Twitter">
                                        <i class="fa fa-twitter"></i>
                                    </a>
                                </li>
                                <li class="rss">
                                    <a href="https://rss.com/" data-toggle="tooltip" target="_blank" title="RSS">
                                        <i class="fa fa-rss"></i>
                                    </a>
                                </li>
                                <li class="google-plus">
                                    <a href="https://www.plus.google.com/discover" data-toggle="tooltip" target="_blank" title="Google +">
                                        <i class="fa fa-google-plus"></i>
                                    </a>
                                </li> --}}
                                <li class="facebook">
                                    <a href="{{ settings('facebook') }}" data-toggle="tooltip" target="_blank" title="Facebook">
                                        <i class="fa fa-facebook"></i>
                                    </a>
                                </li>
                                {{-- <li class="youtube">
                                    <a href="https://www.youtube.com/" data-toggle="tooltip" target="_blank" title="Youtube">
                                        <i class="fa fa-youtube"></i>
                                    </a>
                                </li>
                                <li class="instagram">
                                    <a href="https://www.instagram.com/" data-toggle="tooltip" target="_blank" title="Instagram">
                                        <i class="fa fa-instagram"></i>
                                    </a>
                                </li> --}}
                            </ul>
                        </div>
                    </div>
                    <!-- Begin Footer Block Area -->
                    <div class="col-lg-4">
                       
                        <!-- Begin Footer Newsletter Area -->
                        <div class="footer-newsletter">
                            <h4>{{ __('messages.Sign up to newsletter') }}</h4>
                            <form action="/{{ app()->getlocale() }}/subscribe" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="footer-subscribe-form validate"  novalidate>
                                @csrf
                                <div id="mc_embed_signup_scroll">
                                    <div id="mc-form" class="mc-form subscribe-form form-group" >
                                        <input id="mc-email" type="email" name="email" autocomplete="off" placeholder="{{ __('messages.Enter your email') }}" />
                                        <button class="btn" id="mc-submit">{{ __('messages.Subscribe') }}</button>
                                    </div>
                                </div>
                            </form>
                            @if(session('subscribe_success'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('subscribe_success') }}
                                </div>
                            @elseif(session('subscribe_error'))
                                <div class="alert alert-danger" role="alert">
                                    {{ session('subscribe_error') }}
                                </div>
                            @endif
                        </div>
                        <!-- Footer Newsletter Area End Here -->
                    </div>
                    <!-- Footer Block Area End Here -->
                </div>
            </div>
        </div>
    </div>
    <!-- Footer Static Middle Area End Here -->
</div>
<!-- Footer Area End Here -->