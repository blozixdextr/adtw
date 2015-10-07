@extends('app.layouts.twitcher')

@section('content')
    <h1>My Referrals</h1>
    <p>My referrer link is <strong>{{ url('/ref/'.$user->nickname) }}</strong></p>
    <table class="table">
        <tr>
            <th>user</th>
            <th>income</th>
        </tr>
    @forelse($referrals as $user)
        <tr>
            <td><a href="/profile/{{ $user->id }}">{{ $user->name }}</a></td>
            <td>${{ number_format($user->getPaidReferralSum(), 2) }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="2"><em>no referrals yet</em></td>
        </tr>
    @endforelse
    </table>
    {!!  $referrals->render()  !!}
@endsection

