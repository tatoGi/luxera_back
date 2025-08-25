<!DOCTYPE html>
<html lang="{{ app()->getlocale() }}">
    <x-website.head  />
   
<body>
    <div class="body-wrapper">
       
        <x-website.header  />
        @include('cookie-consent::index')
        <div id="error-message-cart"  style="display: none;"></div>
        {{ $slot }}
        <x-website.footer />
    </div>

    <script>
        var currentLocale = "{{ app()->getLocale() }}";
    </script>
    <x-website.script />
</body>
</html>