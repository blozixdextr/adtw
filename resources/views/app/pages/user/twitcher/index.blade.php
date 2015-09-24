@extends('app.layouts.twitcher')

@section('head-style')
    <link rel="stylesheet" href="/assets/app/css/views/for-timeline.css">
@endsection

@section('head-js')
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
@endsection

@section('content')

    @if (count($waitingBanners) > 0)
        <div class="panel panel-default booking-table-first">
            <h3 class="panel-heading"><i class="fa fa-clock-o"></i> Waiting banners</h3>
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

    <div class="panel panel-default">
        <h3 class="panel-heading"><i class="fa fa-play-circle"></i> Banners ready to start</h3>
        <div class="panel-body booking-table">
        @foreach($bannerTypes as $bt)
            <div class="row">
                <div class="col-xs-6">{{ $bt->title }}</div>
                @if (isset($banners[$bt->id]) && count($banners[$bt->id]) > 0)
                    <div class="col-xs-6"><a class="btn-white" href="/user/twitcher/banner/show/{{ $bt->id }}">start show with {{ count($banners[$bt->id]) }} banners</a></div>
                @else
                    <div class="col-xs-6"><em>no orders yet :(</em></div>
                @endif
            </div>
        @endforeach
        </div>
    </div>
    <div class="panel panel-default">
        <h3 class="panel-heading"><i class="fa fa-area-chart"></i> Timeline</h3>
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
