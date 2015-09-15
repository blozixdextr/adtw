@extends('admin.layouts.index')

@section('content')

    <h1>Logs</h1>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>date</th>
                <th>IP</th>
                <th>user</th>
                <th>action</th>
                <th>key</th>
                <th>additional</th>
                <th>var</th>
            </tr>
        </thead>
        <tbody>
        @forelse($logs as $l)
            @if (strpos($l->type, 'error') !== false)
                <tr class="danger">
            @else
                <tr>
            @endif
                <td>{{ $l->id }}</td>
                <td>{{ $l->created_at->format('d.m.Y H:i') }}</td>
                <td>{{ $l->ip }}</td>
                <td>
                    @if ($l->user_id > 0)
                        <a href="/admin/user/{{ $l->user_id }}">
                            @if ($l->user)
                                {{ $l->user->name }}
                            @else
                                #{{ $l->user_id }}
                            @endif
                        </a>
                    @else
                        guest
                    @endif
                </td>
                <td>{{ $l->type }}</td>
                <td>{{ $l->key_value }}</td>
                <td>{{ $l->additional_value }}</td>
                <td>
                    @if ($l->var)
                        {{ json_encode($l->var) }}
                    @endif
                </td>
            </tr>

        @empty
            <tr><td colspan="8">no logs yet</td></tr>
        @endforelse
        </tbody>
    </table>

    {!! $logs->render() !!}

@endsection