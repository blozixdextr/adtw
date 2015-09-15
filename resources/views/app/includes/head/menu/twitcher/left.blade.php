<div class="nav-column">
    <h2>Balance: $ {{ number_format($user->balance, 2) }}</h2>
    <h1>Dashboard</h1>
    <div class="nav-settings">
        <p class="nav-column-item"><a href="/user/client">Timeline</a></p>
        <p class="nav-column-item"><a href="/user/twitcher/ads">My Ads</a></p>
        <p class="nav-column-item"><a href="/user/twitcher/billing">Billing</a></p>
    </div>
    <div class="nav-user">
        <h4 class="nav-column-title">User</h4>
        <p class="nav-column-item"><a href="/user/twitcher/profile">My Profile</a></p>
        <p class="nav-column-item"><a href="/user/twitcher/notification">Notifications</a></p>
        <p class="nav-column-item"><a href="/auth/logout">Logout</a></p>
    </div>
</div>
