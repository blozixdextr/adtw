@extends('app.layouts.twitcher')

@section('head-style')
    <link rel="stylesheet" href="/assets/app/libs/color-picker/css/bootstrap-colorpicker.min.css" />
    @if ($showWelcome)
        <link rel="stylesheet" href="/assets/app/libs/shepherd/css/shepherd-theme-arrows.css">
        <link rel="stylesheet" href="/assets/app/css/shared/welcome-twitcher.css">
    @endif
    <link rel="stylesheet" href="/assets/app/css/views/for-timeline.css">
@endsection

@section('head-js')
    @if ($showWelcome)
        <script src="/assets/app/libs/tether/js/tether.min.js"></script>
        <script src="/assets/app/libs/shepherd/js/shepherd.min.js"></script>
        <script src="/assets/app/js/welcome-twitcher.js"></script>
    @endif
    <script src="/assets/app/libs/color-picker/js/bootstrap-colorpicker.min.js"></script>
    <script src="/assets/app/js/views/twitcher-index.js"></script>
@endsection

@section('content')

    @if (count($waitingBanners) > 0)
        <div class="panel panel-default booking-table-first">
            <h2 class="panel-heading"><i class="fa fa-clock-o"></i> Waiting banners</h2>
            <table class="table booking-table panel-body">
                    <tr>
                        <th>#</th>
                        <th>banner</th>
                        <th>client</th>
                        <th>limit</th>
                        <th>actions</th>
                    </tr>
                <tbody>
            @foreach($waitingBanners as $b)
                <tr>
                    <td>{{ $b->id }}</td>
                    <td>{{ $b->type->title }}</td>
                    <td>{{ $b->client->name }}</td>
                    <td>{{ $b->amount_limit }}USD</td>
                    <td><a href="/user/twitcher/banner/review/{{ $b->id }}" class="btn-white middle">review</a></td>
                </tr>
            @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="panel booking-table-first panel-default">
        <h2 class="panel-heading"><i class="fa fa-play-circle"></i> Banners ready to start</h2>
        <div class="panel-body booking-table">
            <div class="col-xs-6">
        @if (count($activeBanners) > 0)
            @forelse($bannerTypes as $bt)
                @if (isset($banners[$bt->id]) && count($banners[$bt->id]) > 0)
                    <div class="row ready-banner active" data-id="{{ $bt->id }}" data-title="{{ $bt->title }}">
                @else
                    <div class="row ready-banner">
                @endif
                    <div class="col-xs-3">
                        {{ $bt->title }}<br>
                        @if (isset($banners[$bt->id]) && count($banners[$bt->id]) > 0)
                            <a class="small" href="/user/twitcher/banner/show/{{ $bt->id }}">review</a>
                        @endif
                    </div>
                    @if (isset($banners[$bt->id]) && count($banners[$bt->id]) > 0)
                        <div class="col-xs-3 text-center"><a class="btn-white middle popup" href="/user/twitcher/banner/show/{{ $bt->id }}">Start show with {{ count($banners[$bt->id]) }} banners</a></div>
                    @else
                        <div class="col-xs-3 text-center"><em>no orders yet :(</em></div>
                    @endif
                </div>
            @empty
                <em>There are no orders yet</em>
            @endforelse
            </div>
            <div class="col-xs-6 col-xs-offset-1 together_color">
                <div>
                    <p><strong>If you want to start with all banners, click here</strong></p>
                    <div><a href="#" id="startPopups" class="btn-white">Start all together</a></div>
                </div>
                <div>
                    <p><strong>Background color for banners</strong></p>
                    <div class="input-group" id="bgColorPicker" style="width:120px;">
                        <input type="text" value="#000000" class="form-control"  id="bgColor" />
                        <span class="input-group-addon"><i></i></span>
                    </div>
                </div>
            </div>
        @else
            <em>There are no orders yet</em>
        @endif
        </div>
    </div>
    
    <div class="help-window">
        <i class="fa fa-exclamation-triangle"></i> <a href="#" onclick="toggle_visibility('foo');">Click to read how it works & rules</a>
        <div class="panel panel-default panel-body" id="foo">
            <p>1) You will get payment only if you show banners on your stream.</p>
            <p>2) Click "Start show with N banners". Start all banners.</p>
            <p>3) Choose your chroma key color and capture banners.</p>
            <p>4) Place banners where you want and start streaming.</p>
            <p>5) Our system calculate viewers and duration of your stream.</p>
            <p>Have questions or advices to us? <i class="fa fa-envelope-o"></i> <a href="https://adtw.ch/contact-us">Contact now</a></p>
        </div>
    </div>
    
    
    <div class="panel panel-default">
        <h2 class="panel-heading"><i class="fa fa-area-chart"></i> Timeline</h2>
        <div class="timeline panel-body">
            @forelse($notifications as $n)
                <div class="timeline-item">
                    <div class="timeline-item-content">
                        <div class="timeline-content-in">
                            <i class="fa fa-{{ getNotificationIcon($n->type) }}"></i>
                            <p>{!! $n->title !!}</p>
                        </div>
                    </div>
                    <div class="timeline-item-date">
                        <div class="timeline-item-date-in">
                            <hgroup>
                                <h3>{{ $n->created_at->format('l') }},</h3>
                                <h5>the {!! $n->created_at->format('j<\s\u\p>S</\s\u\p> \o\f F') !!}</h5>
                                <h3>{{ $n->created_at->format('H:i') }}</h3>
                            </hgroup>
                        </div>
                    </div>
                </div>
            @empty
                <em>no timeline</em>
            @endforelse
        </div>
    </div>

    @if (count($notifications) > 0)
        <div id="timelineAutoload"></div>
        <input type="hidden" value="1" id="timelinePage" name="timelinePage">
    @endif

@endsection
