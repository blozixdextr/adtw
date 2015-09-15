@extends('app.layouts.twitcher')

@section('content')
    <h1>Withdraw log</h1>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>date</th>
                <th>amount</th>
                <th>via</th>
                <th>account</th>
                <th>status</th>
                <th>transaction number</th>
                <th>comment</th>
            </tr>
        </thead>

        @forelse ($withdrawals as $w)
            <tr>
                <td>{{ $w->id }}</td>
                <td>{{ $w->created_at->format('d.m.y') }}</td>
                <td class="text-success">{{ $w->amount.' '.strtoupper($w->currency) }}</td>
                <td>{{ $w->merchant }}</td>
                <td>{{ $w->account }}</td>
                <td>{{ $w->transaction_number }}</td>
                <td>{{ $w->admin_comment }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="7"><em>no withdrawals yet</em></td>
            </tr>
        @endforelse

    </table>

    {!! $withdrawals->render() !!}


@endsection

