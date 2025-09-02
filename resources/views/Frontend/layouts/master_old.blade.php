<!doctype html>
<html lang="en">
@include('Frontend.layouts.head')
<body>

    <div class="main-container">
        @unless(request()->is('register') || request()->is('login') || request()->is('sent-otp') || request()->is('otp-verification') || request()->is('otp-verified') || request()->is('forgot-password') || request()->is('user/reset-password/*')) 
            <div class="{{ (request()->is('request-ride') || request()->is('book-ride') || request()->is('request-ride-someone')) ? 'request-header-shape pb-5 pt-2' : 'header-shape py-3' }}">

                @if (request()->is('request-ride') || request()->is('request-ride-someone'))
                    <div class="wh-car request-car-img">
                        <img src="{{asset('assets/images/r-car.png')}}" alt="" class="img-fluid">
                    </div>
                @endif
                   @if (request()->is('book-ride'))
                    <div class="request-car-img">
                        <img src="{{asset('assets/images/black.png')}}" alt="" class="img-fluid">
                    </div>
                @endif
                @include('Frontend.layouts.header')
            </div>
        @endunless
        @yield('content')
        @unless(request()->is('register') || request()->is('login') || request()->is('sent-otp') || request()->is('otp-verification') || request()->is('otp-verified') || request()->is('forgot-password') || request()->is('user/reset-password/*')) 
            @include('Frontend.layouts.footer')
        @endunless
    </div>
    @include('Frontend.layouts.script')
    @yield('scripts')
</body>
</html>
