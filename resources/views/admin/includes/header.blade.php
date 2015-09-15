<a href="/admin" class="logo">
    <span class="logo-mini"><b>AT</b>CH</span>
    <span class="logo-lg"><b>adtw</b>.ch</span>
</a>
<nav class="navbar navbar-static-top" role="navigation" id="navTop">
    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
    </a>
    <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
            <!-- User Account Menu -->
            <li class="dropdown user user-menu">
                <!-- Menu Toggle Button -->
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <span class="fa fa-user-md"></span>
                    <span class="hidden-xs">{{ Auth::user()->name }}</span>
                </a>
                <ul class="dropdown-menu">
                    <!-- The user image in the menu -->
                    <li class="user-header">
                        <p>
                            {{ Auth::user()->name }}
                            <small>{{ Auth::user()->role }}</small>
                        </p>
                    </li>
                    <li class="user-footer">
                        <div class="pull-right">
                            <a href="/auth/logout" class="btn btn-default btn-flat">Sign out</a>
                        </div>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>