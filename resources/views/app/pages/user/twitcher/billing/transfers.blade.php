@extends('app.layouts.twitcher')

@section('content')
    <div class="panel panel-default">
    <h1 class="panel-heading">Billing log</h1>
    <table class="table panel-body booking-table">
        <tr>
            <th>#</th>
            <th>date</th>
            <th>stream date</th>
            <th>client</th>
            <th>banner</th>
            <th>amount</th>
        </tr>

        @forelse ($transfers as $p)
            <tr>
                <td class="no-border">{{ $p->id }}</td>
                <td class="no-border">{{ $p->created_at->format('d.m.y') }}</td>
                @if ($p->bannerStream)
                    <td class="no-border"><a href="{{ $p->bannerStream->banner->file }}">{{ $p->bannerStream->banner->title }}</a></td>
                    <td class="no-border"><a href="/profile/{{ $p->bannerStream->banner->client_id }}">{{ $p->bannerStream->banner->client->name }}</a></td>
                    <td class="no-border"><a href="/user/twitcher/stream/{{ $p->bannerStream->stream_id }}">{{ $p->bannerStream->stream->time_start->format('d.m.Y') }}</a></td>
                @else
                    <td class="no-border" colspan="3"></td>
                @endif
                <td class="no-border" class="text-success">+ {{ $p->amount.' '.strtoupper($p->currency) }}</td>
            </tr>
        @empty
            <tr>
                <td class="no-border" colspan="6"><em>no transfers yet</em></td>
            </tr>
        @endforelse

    </table>
    </div>

    {!! $transfers->render() !!}


@endsection

