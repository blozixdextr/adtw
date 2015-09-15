@extends('admin.layouts.index')

@section('content')

    <section class="content-header">
        <h1>
            Passports Confirm
        </h1>
    </section>

    <table class="table-striped table">
        <thead>
            <tr>
                <th>#</th>
                <th>date</th>
                <th>user</th>
                <th>passport</th>
                <th>actions</th>
            </tr>
        </thead>
    @forelse($logs as $l)
        <tr>
            <td>{{ $l->id }}</td>
            <td>{{ $l->created_at->format('Y.m.d H:i') }}</td>
            <td>{{ $l->user->name }}</td>
            <td><a target="_blank" href="{{ url(\App\Models\User::passportDir.'/'.$l->key_value) }}">review passport</a></td>
            <td>
                <a class="btn btn-success btn-xs" href="/admin/passport/approve/{{ $l->user_id }}/{{ $l->id }}">approve</a>
                <a class="btn btn-danger btn-xs" href="/admin/passport/decline/{{ $l->user_id }}/{{ $l->id }}">decline</a>
            </td>
        </tr>
    @empty
        <tr><td colspan="5">no result</td></tr>
    @endforelse
    </table>

    {!! $logs->render() !!}

    @endsection