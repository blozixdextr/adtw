@extends('admin.layouts.index')

@section('content')
    <h1>Declined payments for streams</h1>
    <table class="table table-striped">
        <thead>
        <tr>
            <td>#</td>
            <td>stream</td>
            <td>banner</td>
            <td>client</td>
            <td>streamer</td>
            <td>minutes</td>
            <td>amount</td>
            <td>has answer</td>
            <td>actions</td>
        </tr>
        </thead>
        <tbody>
        @forelse($bannerStreams as $b)
            <tr>
                <td>{{ $b->id }}</td>
                <td><a href="/admin/decline/{{ $b->id }}/stream">{{ $b->stream->created_at->format('d.m.Y') }}</a></td>
                <td><a href="{{ $b->banner->file }}">banner</a></td>
                <td><a href="/admin/user/{{ $b->banner->client_id }}">{{ $b->banner->client->name }}</a></td>
                <td><a href="/admin/user/{{ $b->banner->twitcher_id }}">{{ $b->banner->twitcher->name }}</a></td>
                <td>{{ $b->minutes }}</td>
                <td>{{ $b->amount }}USD</td>
                <td>
                    @if ($b->twitcher_comment)
                        yes
                    @else
                        no
                    @endif
                </td>
                <td>
                    <a href="/admin/decline/{{ $b->id }}/show" class="btn btn-xs btn-info">show</a>
                </td>
            </tr>
        @empty
            <tr><td colspan="8"><em>no declined payments</em></td></tr>
        @endforelse
        </tbody>
    </table>

@endsection