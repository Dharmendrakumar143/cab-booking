<!doctype html>
<html lang="en">
@include('admin.layouts.head')
<body style="font-family: poppins;">
    
    @if (Auth::guard('admin')->check())
        <div class="dashboard-start bg-black">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3 side-bar-width pe-0">
                        @include('admin.layouts.sidebar')
                    </div>
                    <div class="col-md-9 content-width">
                        <div class="dashboard-main-content rounded-10 bg-white mt-3 p-4">
                            @include('admin.layouts.header')
                            @yield('content')   
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="main-container">
            <main>
                @yield('content')    
            </main>
        </div>
    @endif 
    @include('admin.layouts.script')
    @yield('scripts')
</body>
</html>
