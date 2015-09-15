<nav class="second-nav">
    <div>
        <header id="header">
            <a href=""><img src="/assets/app/images/gold.gif"></a>
            <hgroup>
                <h1><a href="/user/client">Adtw.ch</a></h1>
                <h2>Balance: $ {{ number_format($user->balance, 2) }}</h2>
            </hgroup>
        </header>
        <ul class="head-nav">
            <li><a class="btn-violet" href="/user/client/search">Buy Ads</a></li>
            <li><a class="btn-violet" href="/user/client/billing">Billing</a></li>
            <li><a class="btn-violet" href="/faq">FAQ</a></li>
            <li class="menu-hidden" ><h1>Settings</h1></li>
            <li class="menu-hidden" ><h2>Account</h2></li>
            <li class="menu-hidden" ><a class="btn-violet" href="/user/client/billing">Billing</a></li>
            <li class="menu-hidden" ><h2>User</h2></li>
            <li class="menu-hidden" ><a class="btn-violet" href="/user/client/profile">Profile</a></li>
            <li class="menu-hidden" ><a class="btn-violet" href="/user/client/notification">Notifications</a></li>
        </ul>
        <div class="handle">Menu <span>&#9776;</span></div>
    </div>
</nav>
