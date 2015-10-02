@extends('app.layouts.twitcher')

@section('head-style')
    <link rel="stylesheet" href="/assets/app/css/libs/hover.css" />
    <link rel="stylesheet" href="/assets/app/css/views/for-billing.css">
@endsection


@section('content')
    <h1><i class="fa fa-bank"></i>Billing</h1>
    <div class="work-column-floats">
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
    <div class="clear"></div>
    <div class="height50"></div>
    <div id="withdraw fields-border">
        <div class="panel panel-default">
        <h2 class="panel-heading"><i class="fa fa-cc-paypal"></i> Withdraw to PayPal</h2>
        <div class="panel-body">
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
                    'min' => 1]) !!} USD
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
        </div>
        </div>
    </div>



@endsection
