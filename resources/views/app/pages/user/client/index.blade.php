@extends('app.layouts.client')

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
                        $.getJSON('/user/client/timeline/', {page: page}, function(data) {
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
    <h1><i class="fa fa-area-chart"></i> Client Timeline</h1>
    <div class="timeline">
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
    @if (count($notifications) > 0)
        <div id="timelineAutoload"></div>
        <input type="hidden" value="1" id="timelinePage" name="timelinePage">
    @endif

@endsection
