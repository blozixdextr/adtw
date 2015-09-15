@extends('admin.layouts.index')

@section('content')

    <section class="content-header">
        <h1>
            Users
        </h1>
    </section>

    <div class="panel panel-default">
        <div class="panel-body">
            <form class="form-inline" action="" method="get">
                <div class="form-group">
                    <label for="userFilterName">Name</label>
                    <input value="{{ $name ? $name : old('name') }}" type="text" class="form-control" name="name" id="userFilterName" placeholder="Jane Doe">
                </div>
                <div class="form-group">
                    <label for="userFilterEmail">Email</label>
                    <input value="{{ $email ? $email : old('email') }}" type="text" class="form-control" name="email" id="userFilterEmail" placeholder="test@test.com">
                </div>
                <div class="form-group checkbox">
                    <label for="userFilterActive">
                        <input value="1" type="checkbox" name="only_active" id="userFilterActive" {{ $onlyActive ? 'checked="checked"' : '' }}>
                        active only
                    </label>
                </div>
                <button type="submit" class="btn btn-primari">filter</button>
            </form>
        </div>
    </div>
    <table class="table-striped table">
        <thead>
            <tr>
                <th>#</th>
                <th>name</th>
                <th>type</th>
                <th>email</th>
                <th>last visit</th>
                <th>actions</th>
            </tr>
        </thead>
    @forelse($users as $u)
        <tr>
            <td>{{ $u->id }}</td>
            <td>
                <a href="/admin/user/{{ $u->id }}">{{ $u->name }}</a>
            </td>
            <td>
                @if ($u->type)
                    {{ $u->type }}
                @else
                    {{ $u->role }}
                @endif
            </td>
            <td>{{ $u->email }}</td>
            <td>
                <span title="{{ $u->last_activity->format('Y-m-d H:i') }}">{{ $u->last_activity->diffForHumans() }}</span>
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
        <tr><td colspan="6">no result</td></tr>
    @endforelse
    </table>

    {!! $users->appends(['name' => $name, 'only_active' => $onlyActive, 'email' => $email])->render() !!}

    @endsection