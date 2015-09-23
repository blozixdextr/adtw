@extends('app.layouts.twitcher')

@section('content')
    <h1><i class="fa fa-twitch"></i> Streams</h1>
    <div class="panel panel-default">
        <h3 class="panel-heading">Need a Title here.</h3>
        <table class="table booking-table panel-body">
            <tr>
                <th>Stream Date</th>
                <th>Status</th>
            </tr>
        @forelse($streams as $s)
            <tr>
                <td><a href="/user/twitcher/stream/{{ $s->id }}">{{ $s->time_start->format('d.m.Y') }}</a></td>
                <td>
                    @if ($s->time_end == null)
                        active
                        @else
                        finished
                    @endif
                </td>
            </tr>
        @empty
            <tr><td colspan="2"><em>no streams yet</em></td></tr>
        @endforelse
        </table>
    </div>
@endsection


