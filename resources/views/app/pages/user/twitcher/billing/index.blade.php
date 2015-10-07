@extends('app.layouts.twitcher')

@section('head-style')
    <link rel="stylesheet" href="/assets/app/css/libs/hover.css" />
    <link rel="stylesheet" href="/assets/app/css/views/for-billing.css">
@endsection


@section('content')
    <div class="panel panel-default">
        <h2 class="panel-heading"><i class="fa fa-bank"></i>Billing</h2>
        <div class="work-column-floats panel-body">
            <div class="left">
                <h3>Available</h3><span>${{ number_format($user->availableBalance(), 2) }}</span>
                <div class="work-column-link">
                    <a class="btn-white" href="/user/twitcher/billing/log">Withdrawal history</a>
                </div>
            </div>
            <div class="right">
                <h3>On Hold</h3><span>${{ number_format($user->balance_blocked, 2) }}</span>
                <div class="work-column-link">
                    <a class="btn-white" href="/user/twitcher/billing/transfers">Payments history</a>
                </div>
            </div>
        </div>
    </div>

    <div class="clear"></div>

    <div id="withdraw" class="fields-border">
        <div class="panel panel-default">
        <h2 class="panel-heading"><i class="fa fa-cc-paypal"></i> Withdraw to PayPal</h2>
        <div class="panel-body">
            @if (!$blockWithdraw)
                {!! Form::open(['url' => '/user/twitcher/billing/withdraw']) !!}
                <div class="form-group {!! ($errors && $errors->has('amount')) ? ' has-error' : '' !!}">
                    {!! Form::label('amount', 'Add to your account', ['class' => 'col-sm-3 control-label']) !!}
                    <div class="col-sm-9">
                        {!! Form::input('number', 'amount', old('amount', floor($user->availableBalance())),
                            ['class' => 'form-control',
                            'placeholder' => 'Amount',
                            'required' => 'required',
                            'style' => 'width:120px;display:inline-block',
                            'max' => number_format($user->availableBalance(), 2),
                            'min' => $minWithdraw]) !!} USD
                        {!! Form::errorMessage('amount') !!}

                    </div>
                </div>
                <p>&nbsp;</p>
                <div class="form-group {!! ($errors && $errors->has('amount')) ? ' has-error' : '' !!}">
                    {!! Form::label('amount', 'Add to your account', ['class' => 'col-sm-3 control-label']) !!}
                    <div class="col-sm-9">
                        {!! Form::input('email', 'account', old('account', $user->email),
                            ['class' => 'form-control',
                            'placeholder' => 'Paypal email address',
                            'required' => 'required']) !!}
                        {!! Form::errorMessage('account') !!}
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-9">
                        <button type="submit" class="btn-white">Withdraw</button>
                    </div>
                </div>
                {!! Form::close() !!}
            @else
                <div class="alert alert-danger" role="alert">{!! $withdrawMessage !!}</div>
            @endif
        </div>
        </div>
    </div>

    <div class="clear"></div>

    <div id="withdraw" class="fields-border">
        <div class="panel panel-default">
            <h2 class="panel-heading"><i class="fa fa-money"></i> Coupons</h2>
            <div class="panel-body">
                {!! Form::open(['url' => '/user/twitcher/billing/coupon']) !!}
                <div class="form-group coupons {!! ($errors && $errors->has('coupon')) ? ' has-error' : '' !!}">
                    {!! Form::label('coupon', 'Have a coupon?', ['class' => 'control-label']) !!}
                    <div>
                        {!! Form::text('coupon', old('coupon'),
                            ['class' => 'form-control',
                            'placeholder' => 'Coupon',
                            'required' => 'required',
                            'style' => 'width:120px;display:inline-block']) !!}
                        {!! Form::errorMessage('coupon') !!}

                    </div>
                    <div class="form-group">
                        <div>
                            <button type="submit" class="need-mt-30 btn-white">Apply coupon</button>
                        </div>
                    </div>
                </div>
                
                {!! Form::close() !!}
            </div>
        </div>
    </div>


@endsection
