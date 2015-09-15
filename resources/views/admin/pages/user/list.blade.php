@extends('admin.layouts.index')

@section('content')

    <section class="content-header">
        <h1>
            Users
        </h1>
    </section>

    <form class="form-inline" action="" method="get">
        <div class="form-group">
            <label for="userFilterName">Name</label>
            <input value="{{ $name ? $name : old('name') }}" type="text" class="form-control" name="name" id="userFilterName" placeholder="Jane Doe">
        </div>
        <div class="form-group checkbox">
            <label for="userFilterActive">
                <input value="1" type="checkbox" name="only_active" id="userFilterActive" {{ $onlyActive ? 'checked="checked"' : '' }}>
                active only
            </label>
        </div>
        <button type="submit" class="btn btn-default">filter</button>
    </form>

    <table class="table-striped table">
        <thead>
            <tr>
                <th>#</th>
                <th>role</th>
                <th>provider</th>
                <th>type</th>
                <th>last visit</th>
                <th>name</th>
                <th>actions</th>
            </tr>
        </thead>
    @forelse($users as $u)
        <tr>
            <td>{{ $u->id }}</td>
            <td>{{ $u->role }}</td>
            <td>{{ $u->provider }}</td>
            <td>
                @if ($u->profile)
                    {{ $u->profile->type }}
                @else
                    -
                @endif
            </td>
            <td>
                @if ($u->profile)
                    <span title="{{ $u->profile->last_activity->format('Y-m-d H:i') }}">{{ $u->profile->last_activity->diffForHumans() }}</span>
                @else
                    -
                @endif
            </td>
            <td>
                <a href="/users/permlink/{{ $u->id }}">{{ $u->name }}</a>
            </td>
            <td>
                @if ($u->is_active)
                    <a class="btn btn-danger btn-xs confirm-delete" href="/admin/user/ban/{{ $u->id }}">ban</a>
                @else
                    <a class="btn btn-success btn-xs confirm-delete" href="/admin/user/unban/{{ $u->id }}">unban</a>
                @endif
            </td>
        </tr>
    @empty
        <tr><td colspan="5">no result</td></tr>
    @endforelse
    </table>

    {!! $users->appends(['name' => $name, 'only_active' => $onlyActive])->render() !!}

    @endsection