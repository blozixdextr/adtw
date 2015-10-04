@extends('admin.layouts.index')

@section('content')

    <section class="content-header">
        <h1>
            User profile
        </h1>
    </section>

    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-body">
                    <table class="table-striped table">
                        <tbody>
                        @foreach($user->getAttributes() as $k => $t)
                            <tr>
                                <th>{{ str_replace('_', ' ', $k) }}</th>
                                <td>
                                    @if (is_array($t) || is_object($t))
                                        <div style="width:300px;height:150px;overflow:auto;">{{ json_encode($t, true) }}</div>
                                    @else
                                        @if (strpos($t, 'O:8:"stdClass"') === 0)
                                            <div style="width:300px;height:150px;overflow:auto;">{{ json_encode(unserialize($t)) }}</div>
                                        @else
                                            {{ $t }}
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    @if ($user->is_active)
                        <a class="btn btn-danger btn-lg confirm-delete" href="/admin/user/{{ $user->id }}/ban">ban</a>
                    @else
                        <a class="btn btn-success btn-lg" href="/admin/user/{{ $user->id }}/unban">unban</a>
                    @endif
                    <a class="btn btn-primary btn-lg" href="/admin/user/{{ $user->id }}/login-as">login</a>
                    <a class="btn btn-info btn-lg" href="/admin/user/{{ $user->id }}/edit">edit</a>

                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">Balance</div>
                <div class="panel-body">
                    <table class="table-striped table">
                        <thead>
                            <tr>
                                <th>Balance</th>
                                <th>Blocked</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $user->balance }} USD</td>
                                <td>{{ $user->balance_blocked }} USD</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">Banners</div>
                <div class="panel-body">
                    <table class="table-striped table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>banner</th>
                            <th>amount</th>
                            <th>limit</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($banners as $b)
                            <tr>
                                <td>{{ $b->id }}</td>
                                <td><a href="{{ $b->file }}">{{ $b->type->title }}</a></td>
                                <td>{{ number_format($b->totalAmount(), 2) }} USD</td>
                                <td>{{ number_format($b->amount_limit, 2) }} USD</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">no banners yet</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">Profile</div>
                <div class="panel-body">
                    <table class="table-striped table">
                        <tbody>
                        @foreach($user->profile->getAttributes() as $k => $t)
                            <tr>
                                <th>{{ str_replace('_', ' ', $k) }}</th>
                                <td>
                                    @if (is_array($t) || is_object($t))
                                        <div style="width:300px;height:150px;overflow:auto;">{{ json_encode($t, true) }}</div>
                                    @else
                                        @if (strpos($t, 'O:8:"stdClass"') === 0)
                                            <div style="width:300px;height:150px;overflow:auto;">{{ json_encode(unserialize($t)) }}</div>
                                        @else
                                            {{ $t }}
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">

        </div>
        <div class="col-md-6"></div>
    </div>

    <div class="clearfix"></div>



    @endsection