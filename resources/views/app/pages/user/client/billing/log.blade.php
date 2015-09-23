@extends('app.layouts.client')

@section('content')
    <div class="panel panel-default booking-table-first">
        <h2 class="panel-heading">Payments <a href="/user/client/billing/transfers">Transfers</a></h2>
        <table class="table panel-body booking-table">
                <tr>
                    <th>#</th>
                    <th>date</th>
                    <th>amount</th>
                    <th>via</th>
                </tr>
            @forelse ($payments as $p)
                <tr>
                    <td>{{ $p->id }}</td>
                    <td>{{ $p->created_at->format('d.m.y') }}</td>
                    <td class="text-success">+ {{ $p->amount.' '.strtoupper($p->currency) }}</td>
                    <td>{{ $p->merchant }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3"><em>no payments yet</em></td>
                </tr>
            @endforelse
        </table>
    </div>

    {!! $payments->render() !!}


@endsection

