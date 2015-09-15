<div class="nav-column">
    <h1>Dashboard</h1>
    <h2>Balance: $ {{ number_format($user->balance, 2) }}</h2>
    <div class="nav-settings">
        <p class="nav-column-item"><a href="/user/client">Timeline</a></p>
        <p class="nav-column-item"><a href="/user/client/search">Search & Buy</a></p>
        <p class="nav-column-item"><a href="/user/client/ads">My Ads</a></p>

    </div>
    <div class="nav-user">
        <h4 class="nav-column-title">User</h4>
        <p class="nav-column-item"><a href="/user/client/billing">Billing</a></p>
        <p class="nav-column-item"><a href="/user/client/profile">My Profile</a></p>
        <p class="nav-column-item"><a href="/user/client/notification">Notifications</a></p>
        <p class="nav-column-item"><a href="/auth/logout">Logout</a></p>
    </div>
</div>
