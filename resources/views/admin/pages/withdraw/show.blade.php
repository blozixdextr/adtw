@extends('admin.layouts.index')

@section('content')
    <h1>Withdrawal info</h1>
    <p>User: <a href="/admin/user/{{ $withdrawal->user_id }}" class="btn btn-info">
        @if ($withdrawal->user)
            {{ $withdrawal->user->name }}
        @else
            #{{ $withdrawal->user_id }}
        @endif
    </a></p>
    @if ($withdrawal->response)
        <p>Withdrawed: {{ $withdrawal->response['batch_header']['amount']['value'].$withdrawal->response['batch_header']['amount']['currency'] }}</p>
        <p>PayPal fee: {{ $withdrawal->response['batch_header']['fees']['value'].$withdrawal->response['batch_header']['fees']['currency'] }}</p>
    @endif
    <div class="panel panel-default">
        <div class="panel-body">
            <table class="table-striped table">
                <tbody>
                @foreach($withdrawal->getAttributes() as $k => $t)
                    <tr>
                        <th>{{ str_replace('_', ' ', $k) }}</th>
                        <td>
                            @if (is_array($t) || is_object($t))
                                <div style="width:300px;height:150px;overflow:auto;">{{ json_encode($t, true) }}</div>
                            @else
                                @if (strpos($t, 'a:') === 0)
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

@endsection