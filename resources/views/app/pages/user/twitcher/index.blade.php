@extends('app.layouts.twitcher')

@section('head-style')
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
    <script>
        $(function(){
            $(window).scroll(function() {
                if ($('#timelineAutoload').hasClass('loading')) {
                    return;
                }
                var hT = $('#timelineAutoload').offset().top,
                        hH = $('#timelineAutoload').outerHeight(),
                        wH = $(window).height(),
                        wS = $(this).scrollTop();
                if (wS > (hT + hH - wH)){
                    var page = parseInt($('#timelinePage').val());
                    if (page > 0) {
                        page++;
                        $('#timelineAutoload').addClass('loading');
                        $.getJSON('/user/twitcher/timeline/', {page: page}, function(data) {
                            $('#timelineAutoload').removeClass('loading');
                            if (data.html) {
                                $('.timeline').append(data.html);
                                $('#timelinePage').val(page);
                            } else {
                                $('#timelinePage').val(0);
                            }
                        });
                    }
                }
            });

        });
    </script>

    <script>
        $(function(){
            function openBannerPopUp(bannerTypeId, width, height) {
                var url = '/user/twitcher/banner/popup/' + bannerTypeId + '?color=fffff';
                window.open(url, 'name_' + bannerTypeId, 'width=' + width + ',height=' + height);
            }
            $('#startPopups').click(function(e){
                e.preventDefault();
                $('.booking-table .ready-banner.active').each(function(i, el){
                    el = $(el);
                    var d = el.data('title');
                    var dimensions = d.split('*');
                    setTimeout(function(){
                        openBannerPopUp(el.data('id'), dimensions[0], dimensions[1]);
                    }, i*500);

                });

            });
        });
    </script>

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
                    <td><a href="/user/twitcher/banner/review/{{ $b->id }}" class="btn-white little">review</a></td>
                </tr>
            @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="panel booking-table-first panel-default">
        <h2 class="panel-heading"><i class="fa fa-play-circle"></i> Banners ready to start</h2>
        <div class="panel-body booking-table">
        @if (count($activeBanners) > 0)
            @forelse($bannerTypes as $bt)
                @if (isset($banners[$bt->id]) && count($banners[$bt->id]) > 0)
                    <div class="row ready-banner active" data-id="{{ $bt->id }}" data-title="{{ $bt->title }}">
                @else
                    <div class="row ready-banner">
                @endif
                    <div class="col-xs-6">{{ $bt->title }}</div>
                    @if (isset($banners[$bt->id]) && count($banners[$bt->id]) > 0)
                        <div class="col-xs-6"><a class="btn-white" href="/user/twitcher/banner/show/{{ $bt->id }}">Start show with {{ count($banners[$bt->id]) }} banners</a></div>
                    @else
                        <div class="col-xs-6"><em>no orders yet :(</em></div>
                    @endif
                </div>
            @empty
                <em>There are no orders yet</em>
            @endforelse
            <div class="row">
                <a href="#" id="startPopups" class="btn-white">Start all together</a>
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
