@extends('admin.layouts.index')

@section('content')
    <h1>All withdrawals <small> / <a href="/admin/withdraw">waiting only</a></small></h1>
    <table class="table table-striped">
        <thead>
        <tr>
            <td>#</td>
            <td>streamer</td>
            <td>amount</td>
            <td>merchant</td>
            <td>account</td>
            <td>status</td>
            <td>actions</td>
        </tr>
        </thead>
        <tbody>
        @forelse($withdrawals as $w)
            <tr>
                <td>{{ $w->id }}</td>
                <td>
                    <a href="/admin/user/{{ $w->user_id }}">
                        @if ($w->user)
                            {{ $w->user->name }}
                        @else
                            #{{ $w->user_id }}
                        @endif
                    </a>
                </td>
                <td>{{ $w->amount }} {{ $w->currency }}</td>
                <td>{{ $w->merchant }}</td>
                <td>{{ $w->account }}</td>
                <td>{{ $w->status }}</td>
                <td>
                    <a href="/admin/withdraw/{{ $w->id }}/show" class="btn btn-xs btn-info">show</a>
                </td>
            </tr>
        @empty
            <tr><td colspan="7"><em>no withdrawals</em></td></tr>
        @endforelse
        </tbody>

    </table>

@endsection