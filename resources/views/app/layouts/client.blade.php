<!DOCTYPE HTML>
<html>
<head>
    <title>@yield('title', 'ADTW')</title>
    @include('app.includes.head.meta')
    @include('app.includes.head.styles')
    @include('app.includes.head.scripts')
    @include('app.includes.head.ga')
    @yield('head-style')
    @yield('head-js')
</head>
<body id="top">
    <div class="second-page">
        <wrapper>
            @include('app.includes.head.menu.client.top')
            <div class="page">
                @include('app.includes.head.menu.client.left')
                <div class="work-column">
                    @include('app.includes.alerts')
                    @include('app.includes.errors')
                    @yield('content')
                </div>
                <div class="clear"></div>
            </div>
        </wrapper>
        @include('app.includes.footer')
    </div>
    @yield('body-js')
</body>
</html>