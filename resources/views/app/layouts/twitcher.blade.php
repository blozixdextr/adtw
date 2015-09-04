<!DOCTYPE HTML>
<html>
<head>
    <title>@yield('title', 'ADTW')</title>
    @include('app.includes.head.meta')
    @include('app.includes.head.styles')
    @include('app.includes.head.scripts')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    @include('app.includes.head.ga')
    @yield('head-style')
    @yield('head-js')
</head>
<body id="top">
    <div class="second-page">
        <wrapper>
            @include('app.includes.head.menu.twitcher.top')
            <div class="page">
                @include('app.includes.head.menu.twitcher.left')
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