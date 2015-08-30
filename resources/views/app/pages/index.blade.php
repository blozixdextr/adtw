<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>ADTW.CH</title>

    <link href='http://fonts.googleapis.com/css?family=Rancho' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Roboto+Slab:400,700' rel='stylesheet' type='text/css'>


    <link rel="stylesheet" type="text/css" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/app/css/libs/hover.css" />

    <link rel="stylesheet" href="/assets/app/css/shared/modal.css" />
    <link rel="stylesheet" href="/assets/app/css/shared/main.css" />

    <link rel="stylesheet" href="/assets/app/css/views/for-main.css">

    <!--[if lte IE 9]>
    <link rel="stylesheet" href="/assets/app/css/libs/for-IE.css">
    <![endif]-->

</head>
<body style="zoom: 1;">
<div class="main-page">
    <wrapper>
        <header id="header" class="text-center">
            <div class="shadow-fon">
                <a href=""><img src="/assets/app/images/gold.gif"></a>
                <a href=""><h1>Adtw.ch</h1></a><br>
                <span class="title-intro">Ad exchange for<br></span>
                <span class="title-descr">streamers & adverisers</span>
            </div>
        </header>
        <nav class="main-nav">
            <ul class="head-nav">
                <li><a class="btn-violet" href="/my-ads">My Ads</a></li>
                <li><a class="btn-violet" href="/billing">Billing</a></li>
                <li><a class="btn-violet" href="/referrals">Referrals</a></li>
                <li><a class="btn-violet" href="/faq">FAQ</a></li>
                <li class="menu-hidden" ><h1>Settings</h1></li>
                <li class="menu-hidden" ><h2>Account</h2></li>
                <li class="menu-hidden" ><a class="btn-violet" href="/">Billing</a></li>
                <li class="menu-hidden" ><a class="btn-violet" href="/team">Team</a></li>
                <li class="menu-hidden" ><a class="btn-violet" href="/referrals">Referrals</a></li>
                <li class="menu-hidden" ><h2>User</h2></li>
                <li class="menu-hidden" ><a class="btn-violet" href="/profile">Profile</a></li>
                <li class="menu-hidden" ><a class="btn-violet" href="/security">Security</a></li>
                <li class="menu-hidden" ><a class="btn-violet" href="/notifications">Notifications</a></li>
            </ul>
            <div class="handle">Menu <span>&#9776;</span></div>
        </nav>
        <div class="page">
            <div class="text-center intro-main">
                <div class="title-place-left">
                    <img src="/assets/app/images/ts1.png" alt="">
                    <div class="choose-link-descr">
                        <h4><i class="fa fa-bullhorn"></i> Ad Buyers</h4>
                        <p> Reach your audience</p><p> Superior Ad Formats</p>
                        <div class="choose-link">
                            <p>I am advertizer</p>
                            <label class="btn-white" for="modal-1">Buy Ads</label>
                        </div>
                    </div>
                </div>
                <div class="title-place-right">
                    <img src="/assets/app/images/ts2.png" alt="">
                    <div class="choose-link-descr">
                        <h4><i class="fa fa-twitch"></i> Streamers</h4>
                        <p> Earn $ to your needs</p>
                        <p> Adtw.ch helps you !</p>
                        <div class="choose-link">
                            <p>I am streamer</p>
                            <label class="btn-white" for="modal-2">Earn Money</label>
                        </div>
                    </div>
                </div>
                <div class="modal">
                    <input class="modal-open" id="modal-1" type="checkbox" hidden>
                    <div class="modal-wrap" aria-hidden="true" role="dialog">
                        <label class="modal-overlay" for="modal-1"></label>
                        <div class="modal-dialog">
                            <div class="modal-header">
                                <h2>Please enter your e-mail</h2>
                                <label class="btn-close" for="modal-1" aria-hidden="true">×</label>
                            </div>
                            <div class="modal-body">
                                <form action="" class="enter-email">
                                    <input type="email" placeholder="your@email.com">
                                    <button>Accept</button>
                                    <div class="clear"></div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal">
                    <input class="modal-open" id="modal-2" type="checkbox" hidden>
                    <div class="modal-wrap" aria-hidden="true" role="dialog">
                        <label class="modal-overlay" for="modal-2"></label>
                        <div class="modal-dialog">
                            <div class="modal-header">
                                <h2>Login by your Twitch account</h2>
                                <label class="btn-close" for="modal-2" aria-hidden="true">×</label>
                            </div>
                            <div class="modal-body">
                                <div class="twitch-login">
                                    <a href="/auth/twitch" class="btn-white" id="twitchLoginBtn">Login</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <center><h1>How it works?</h1></center>
            <div class="education-steps">
                <div class="step">
                    <div><img src="/assets/app/images/face1.png"></div>
                    <h3>Choose Best<br><span>Streamers</span></h3>
                    <p>Ad buyer can select targeting language of streamer, number of followers and game of streamer.</p>
                </div>
                <div class="step">
                    <div><img src="/assets/app/images/face2.png"></div>
                    <h3>Buy <span>Ads</span> &<br> upload banner</h3>
                    <p>Order ads & upload banner. Pay only for time of streaming. Pay per hour model</p>
                </div>
                <div class="step">
                    <div><img src="/assets/app/images/face3.png"></div>
                    <h3>Streamer <span>Place</span><br>Banner</h3>
                    <p>Our system control banner placement and ads buyer pay only for hours of banner placement.</p>
                </div>
            </div>
            <center><h1>Why buy ads on adtw.ch?</h1></center>
            <div class="about-us">
                <ul>
                    <li><i class="fa fa-file-text-o"></i><h2>Lorem ipsum.</h2><p>Viewers want to try products or services that streamer promote.</p></li>
                    <li><i class="fa fa-money"></i><h2>Pay per hours.</h2><p>Streamer control system make screenshots and ads buyer pay only for streaming hours.</p></li>
                    <li><i class="fa fa-twitch"></i><h2>Best Twitch Streamers.</h2><p>Ads buyers can choose best & most trusted Twitch streamers to place banners.</p></li>
                    <li><i class="fa fa-gamepad"></i><h2>Lorem ipsum.</h2><p>Viewers want to try products or services that streamer promote.</p></li>
                </ul>
            </div>
        </div>
    </wrapper>
    <footer id="footer">
        <ul class="icons">
            <li><a href="#" class="fa fa-twitter"><span class="label"> Twitter</span></a></li>
            <li><a href="#" class="fa fa-github"><span class="label"> Github</span></a></li>
            <li><a href="#" class="fa fa-dribbble"><span class="label"> Dribbble</span></a></li>
            <li><a href="#" class="fa fa-envelope-o"><span class="label"> Email</span></a></li>
        </ul>
        <ul class="copyright">
            <li>&copy; Untitled</li>
        </ul>
    </footer>
</div>

<script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="/assets/app/js/menu.js"></script>
<script src="https://ttv-api.s3.amazonaws.com/twitch.min.js"></script>
<!--
<script>
    var twitch_client_id = '{{ Config::get('twitch.client_id') }}';
</script>
<script src="/assets/app/js/twitch.js"></script>
-->

</body>
</html>