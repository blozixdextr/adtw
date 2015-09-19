@foreach($notifications as $n)
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
@endforeach