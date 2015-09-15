<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>@yield('head-title', 'Admin Panel')</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    @include('admin.includes.styles')
    @yield('head-style')
    @yield('head-js')
</head>
<body class="skin-black sidebar-mini">
    <div class="wrapper">
        <header class="main-header">@include('admin.includes.header')</header>

        <aside class="main-sidebar">
            <section class="sidebar">@include('admin.includes.sidebar-menu')</section>
        </aside>

        <div class="content-wrapper">
            @include('admin.includes.errors')
            @include('admin.includes.alerts')
            <section class="content">
                @yield('content')
            </section>
        </div>

        <footer class="main-footer">@include('admin.includes.footer')</footer>
    </div>
    @include('admin.includes.scripts')
    @yield('body-js')
</body>
</html>