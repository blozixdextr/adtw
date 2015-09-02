@extends('app.layouts.index')

@section('head-style')
    <link rel="stylesheet" href="/assets/app/css/views/for-main.css">
    <link rel="stylesheet" href="/assets/app/css/shared/modal.css">
@endsection

@section('content')
    <div class="text-center intro-main">
        @if (!$user)
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

                        {!! Form::open(['url' => '/auth/client', 'class' => 'enter-email']) !!}
                            <input type="email" placeholder="your@email.com" name="email">
                            <button type="submit">Accept</button>
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
    @else
        <h1>Hello {{ $user->name }}.</h1>
        <p>You will be redirected to <a href="{{ url('/user/'.$user->type) }}">profile</a> in 10 seconds</p>
    @endif
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