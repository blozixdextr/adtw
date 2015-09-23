@extends('app.layouts.twitcher')

@section('content')
    <h1 class="panel-heading">Streams</h1>
    <div class="panel panel-default">
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


