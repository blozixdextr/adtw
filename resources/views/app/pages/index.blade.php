@extends('app.layouts.index')

@section('head-style')
    <link rel="stylesheet" href="/assets/app/css/views/for-main.css">
    <link rel="stylesheet" href="/assets/app/css/shared/modal.css">
@endsection

@section('content')

    @if ($user)
        <div style="text-align: center">
            <h1>Hello {{ $user->name }}.</h1>
            <p>You will be redirected to <a href="{{ url('/user/'.$user->type) }}">profile</a> in 10 seconds</p>
        </div>
    @endif

    <center><h1>Simple & Powerful Banner Ads Platform </h1></center>
    <div class="window-bg bottom-info-one">
        <div class="bottom-info-content left">
            <div>
                <h2>Start your advertising campaign<br> with top Twitch.tv streamers</h2>
                <p>Control your ads with simple panel</p>
            </div>
            <ul>
                <li>Pay as you go</li>
                <li>Powerful control</li>
                <li>Low price</li>
                <li>Easy to start</li>
                <li>Huge effect</li>
                <li>Pay per 1 viewer/hour</li>
            </ul>
            <div>
                <a href="#ready" class="btn-white">Create Free Account</a>
            </div>
        </div>
        <div class="bottom-info-img right">
            <img src="https://cloud.githubusercontent.com/assets/2786905/9975849/7d07bb4e-5ecc-11e5-98fd-1d179372aa4a.gif" />
        </div>
        <div class="clear"></div>
    </div>

    <center><h1>How it works?</h1></center>
    <div class="education-steps window-bg">
        <div class="step">
            <div><img src="/assets/app/images/face1.png"></div>
            <h3>Choose Best Streamers</h3>
            <p>Ad buyer can select targeting language of streamer, number of followers and game of streamer.</p>
        </div>
        <div class="step">
            <div><img src="/assets/app/images/face2.png"></div>
            <h3>Buy Ads & upload banner</h3>
            <p>Order ads & upload banner. Pay only for time of streaming. Pay per hour model</p>
        </div>
        <div class="step">
            <div><img src="/assets/app/images/face3.png"></div>
            <h3>Streamer Place Banner</h3>
            <p>Our system control banner placement and ads buyer pay only for hours of banner placement.</p>
        </div>
    </div>

<center><h1>Why buy ads on adtw.ch?</h1></center>
    <div class="about-us window-bg">
        <ul>
            <li><i class="fa fa-line-chart"></i><h2>Analyze Your<br />Campaigns</h2><p>Easy to use & powerful control panel gives you all information about your banner ads.</p></li>
            <li><i class="fa fa-money"></i><h2>Pay per<br /> 1 viewer/hour</h2><p>Streamers control system make screenshots and ads buyer pay only for streaming hours.</p></li>
            <li><i class="fa fa-twitch"></i><h2>Best Twitch<br /> Streamers</h2><p>Ads buyers can choose best & most trusted Twitch streamers to place banners.</p></li>
            <li><i class="fa fa-gamepad"></i><h2>Relevant Streams<br /> for Your Ads</h2><p>Viewers want to try products or services that streamer promote and use your product.</p></li>
        </ul>
    </div>
    
    <center><h1>Create and Manage Ads with Best Streamers</h1></center>
    <div class="window-bg bottom-info-one">
        <div class="bottom-info-content left">
            <div>
                <h2>Single point of contact<br /> for hundreds of streamers</h2>
                <p>User-friendly and powerful control panel offers easy to use ads management</p>
            </div>
            <ul>
                <li>Real-time performance tracking</li>
                <li>Simple streamers control</li>
                <li>Target specific games & languages</li>
                <li>Consolidated billing, reporting, and management</li>
                <li>24x7 Technical Support</li>
            </ul>

        </div>
        <div class="bottom-info-img right">
            <img src="https://cloud.githubusercontent.com/assets/2786905/9976042/9ba83148-5ed4-11e5-8c46-dc27ef27194f.gif" />
        </div>
        <div class="clear"></div>
    </div>



    <center><h1 id="ready">Are you ready?</h1></center>
    <div class="text-center intro-main">
        @if (!$user)
        <div class="title-place-left window-bg-tiny">
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
        <div class="title-place-right window-bg-tiny">
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

                        {!! Form::open(['url' => '/auth/client', 'class' => 'enter-email']) !!}
                            <input type="email" placeholder="your@email.com" name="email">
                            <div class="clear"></div>
                            <input type="password" placeholder="Your password" name="password">
                            <div class="clear"></div>
                            <button type="submit" name="operation" value="register">Register</button>
                            <button type="submit" name="operation" value="login">Login</button>
                            <div class="clear"></div>
                        {!! Form::close() !!}

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
    @endif


@endsection

@if ($user)
    @section('head-js')
        <script>
            setTimeout(function(){
                location.href = '/user/{{ $user->type }}';
            }, 10000);
        </script>
    @endsection
@endif
