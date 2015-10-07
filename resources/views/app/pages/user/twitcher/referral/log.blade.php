@extends('app.layouts.twitcher')

@section('content')
    <h1>Referral payments</h1>
    <p>My referrer link is <strong>{{ url('/ref/'.$user->nickname) }}</strong></p>
    <table class="table">
        <tr>
            <th>date</th>
            <th>user</th>
            <th>income</th>
        </tr>
        @forelse($payments as $payment)
            <tr>
                <td>{{ $payment->created_at->format('Y-m-d H:i') }}</td>
                <td><a href="/profile/{{ $payment->user_id }}">{{ $payment->user->name }}</a></td>
                <td>${{ number_format($payment->amount, 2) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="3"><em>no referrals yet</em></td>
            </tr>
        @endforelse
    </table>
    {!!  $payments->render()  !!}
@endsection

