@extends('app.layouts.twitcher')

@section('content')
    <div class="panel panel-default booking-table-first">
    <h1 class="panel-heading">Withdraw log</h1>
    <table class="table panel-body booking-table">
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

        @forelse ($withdrawals as $w)
            @if ($w->status == 'done')
                <tr class="success">
            @else
                @if ($w->status == 'declined')
                    <tr class="danger">
                @else
                    <tr>
                @endif
            @endif

                <td>{{ $w->id }}</td>
                <td>{{ $w->created_at->format('d.m.y') }}</td>
                <td class="text-success">{{ $w->amount.' '.strtoupper($w->currency) }}</td>
                <td>{{ $w->merchant }}</td>
                <td>{{ $w->account }}</td>
                <td>{{ $w->status }}</td>
                <td>{{ $w->transaction_number }}</td>
                <td>{{ $w->admin_comment }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="7"><em>no withdrawals yet</em></td>
            </tr>
        @endforelse

    </table>
    </div>

    {!! $withdrawals->render() !!}


@endsection

