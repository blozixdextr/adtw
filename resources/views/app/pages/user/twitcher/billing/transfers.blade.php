@extends('app.layouts.twitcher')

@section('content')
    <h1>Billing log</h1>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>date</th>
                <th>stream date</th>
                <th>client</th>
                <th>banner</th>
                <th>amount</th>
            </tr>
        </thead>

        @forelse ($transfers as $p)
            <tr>
                <td>{{ $p->id }}</td>
                <td>{{ $p->created_at->format('d.m.y') }}</td>
                @if ($p->bannerStream)
                    <td><a href="{{ $p->bannerStream->banner->file }}">{{ $p->bannerStream->banner->title }}</a></td>
                    <td><a href="/profile/{{ $p->bannerStream->banner->client_id }}">{{ $p->bannerStream->banner->client->name }}</a></td>
                    <td><a href="/user/twitcher/stream/{{ $p->bannerStream->stream_id }}">{{ $p->bannerStream->stream->time_start->format('d.m.Y') }}</a></td>
                @else
                    <td colspan="3"></td>
                @endif
                <td class="text-success">+ {{ $p->amount.' '.strtoupper($p->currency) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="6"><em>no transfers yet</em></td>
            </tr>
        @endforelse

    </table>

    {!! $transfers->render() !!}


@endsection

