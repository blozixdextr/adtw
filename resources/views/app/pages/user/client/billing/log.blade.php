@extends('app.layouts.client')

@section('content')
    <h1>Billing log</h1>
    <h2>Payments</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>date</th>
                <th>amount</th>
            </tr>
        </thead>

        @forelse ($payments as $p)
            <tr>
                <td>{{ $p->id }}</td>
                <td>{{ $p->created_at->format('d.m.y') }}</td>
                <td class="text-success">+ {{ $p->amount.' '.strtoupper($p->currency) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="3"><em>no payments yet</em></td>
            </tr>
        @endforelse

    </table>

    {!! $payments->render() !!}


@endsection
