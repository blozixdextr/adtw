<div class="nav-column">
    <h2>Balance:<br> $ {{ number_format($user->balance, 2) }}</h2>
    <h2>Dashboard</h2>
    <div class="nav-settings">
        <p class="nav-column-item"><i class="fa fa-area-chart"></i> <a href="/user/client">Timeline</a></p>
        <p class="nav-column-item"><i class="fa fa-list-alt"></i> <a href="/user/twitcher/ads">My Ads</a></p>
        <p class="nav-column-item"><i class="fa fa-money"></i> <a href="/user/twitcher/billing">Billing</a></p>
    </div>
    <div class="nav-user">
        <h4 class="nav-column-title">User</h4>
        <p class="nav-column-item"><i class="fa fa-user"></i> <a href="/user/twitcher/profile">My Profile</a></p>
        <p class="nav-column-item"><i class="fa fa-commenting-o"></i> <a href="/user/twitcher/notification">Notifications</a></p>
        <p class="nav-column-item"><i class="fa fa-sign-out"></i> <a href="/auth/logout">Logout</a></p>
    </div>
</div>
