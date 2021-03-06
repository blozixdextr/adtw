<!DOCTYPE html>
<html lang="en">
<head>
    <title>@yield('title', 'ADTW')</title>
    @include('app.includes.head.meta')
    @include('app.includes.head.styles')
    @include('app.includes.head.scripts')
    @include('app.includes.head.ga')
    @yield('head-style')
    @yield('head-js')
</head>
<body style="zoom: 1;">
<div class="main-page">
    <div class="main-back">
        <wrapper>
            @include('app.includes.head.menu.guest.top')
            <div class="page">
                <div class="in-page">
                    @include('app.includes.alerts')
                    @include('app.includes.errors')
                    @yield('content')
                </div>
            </div>
        </wrapper>
    </div>
    @include('app.includes.footer')
</div>
@yield('body-js')
</body>
</html>
