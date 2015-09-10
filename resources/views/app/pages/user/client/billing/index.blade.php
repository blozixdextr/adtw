@extends('app.layouts.client')

@section('head-style')
    <link rel="stylesheet" href="/assets/app/css/libs/hover.css" />
    <link rel="stylesheet" href="/assets/app/css/views/for-billing.css">
@endsection


@section('content')
    <h1>Billing</h1>
    <div class="work-column-floats">
        <div class="left">
            <h3>You Owe</h3><span>${{ number_format($user->balance_blocked, 2) }}</span>
            <div class="work-column-link">
                <a class="btn-white" href="#payments">Refill your account</a>
            </div>
        </div>
        <div class="right">
            <h3>Usage</h3><span>${{ number_format(0, 2) }}</span>
            <div class="work-column-link">
                <a class="btn-white" href="/user/client/billing/log">View Usage Details</a>
            </div>
        </div>
    </div>
    <div class="clear"></div>
    <div class="work-column-item" id="payments">
        <h3>Payment Methods</h3>

        <div class="tabs">
            <input id="tab1" type="radio" name="tabs" class="tab" checked="">
            <label for="tab1" title="Credit Cards" class="tab">Credit Cards</label>

            <input id="tab2" type="radio" name="tabs" class="tab">
            <label for="tab2" title="Paypal" class="tab">Paypal</label>
            <section id="content1">
                <div class="sect-content violet">
                        <div class="row-wrap">
                            {!! Form::open(['url' => '/user/client/billing/card', 'class' => 'form-horizontal']) !!}
                            <div class="form-group {!! ($errors && $errors->has('amount')) ? ' has-error' : '' !!}">
                                {!! Form::label('amount', 'Add to your account', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-9">
                                    {!! Form::text('amount', old('amount', 10), ['class' => 'form-control', 'placeholder' => 'Amount', '00.00' => 'required', 'style' => 'width:120px;display:inline-block']) !!} USD
                                    {!! Form::errorMessage('amount') !!}
                                </div>
                            </div>
                            <div class="form-group {!! ($errors && $errors->has('card_number')) ? ' has-error' : '' !!}">
                                {!! Form::label('card_number', 'Card number', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-9">
                                    {!! Form::text('card_number', old('card_number'), ['class' => 'form-control', 'placeholder' => '0000000000000000', 'required' => 'required']) !!}
                                    {!! Form::errorMessage('card_number') !!}
                                </div>
                            </div>
                            <div class="form-group {!! ($errors && $errors->has('card_holder')) ? ' has-error' : '' !!}">
                                {!! Form::label('card_holder', 'Cardholder', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-9">
                                    {!! Form::text('card_holder', old('card_holder', $cardholderName), ['class' => 'form-control', 'placeholder' => 'Your Name', 'required' => 'required']) !!}
                                    {!! Form::errorMessage('card_holder') !!}
                                </div>
                            </div>
                            <div class="form-group {!! ($errors && $errors->has('card_holder')) ? ' has-error' : '' !!}">
                                {!! Form::label('card_year', 'Vaild till', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-9">
                                    <div class="col-xs-6">
                                        <div class="form-group {!! ($errors && $errors->has('card_year')) ? ' has-error' : '' !!}">
                                            {!! Form::label('card_year', 'Year', ['class' => 'col-sm-3 control-label']) !!}
                                            <div class="col-sm-9">
                                                {!! Form::text('card_year', old('card_year'), ['class' => 'form-control', 'placeholder' => 'YYYY', 'required' => 'required', 'style' => 'width:120px']) !!}
                                                {!! Form::errorMessage('card_year') !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="form-group {!! ($errors && $errors->has('card_month')) ? ' has-error' : '' !!}">
                                            {!! Form::label('card_month', 'Month', ['class' => 'col-sm-3 control-label']) !!}
                                            <div class="col-sm-9">
                                                {!! Form::text('card_month', old('card_month'), ['class' => 'form-control', 'placeholder' => 'MM', 'required' => 'required', 'style' => 'width:120px']) !!}
                                                {!! Form::errorMessage('card_month') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group {!! ($errors && $errors->has('card_cvc')) ? ' has-error' : '' !!}">
                                {!! Form::label('card_cvc', 'Card CVC', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-9">
                                    {!! Form::text('card_cvc', old('card_cvc'), ['class' => 'form-control', 'placeholder' => '000', 'required' => 'required', 'style' => 'width:120px']) !!}
                                    {!! Form::errorMessage('card_cvc') !!}
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-9">
                                    <button type="submit" class="btn btn-default">Pay now</button>
                                </div>
                            </div>
                            {!! Form::close() !!}
                            @if (\Config::get('app.debug'))
                            <small>testing 'number' => '4242424242424242', 'exp_month' => 10, 'exp_year' => 2015, 'cvc' => 333, 'name' => 'Test Tester'</small>
                            @endif
                        </div>
            </section>
            <section id="content2">
                <div class="sect-content violet">
                    <div class="row">
                        {!! Form::open(['url' => '/user/client/billing/paypal', 'class' => 'form-horizontal']) !!}
                        <div class="form-group {!! ($errors && $errors->has('amount')) ? ' has-error' : '' !!}">
                            {!! Form::label('amount', 'Add to your account', ['class' => 'col-sm-3 control-label']) !!}
                            <div class="col-sm-4">
                                {!! Form::text('amount', old('amount', 10), ['class' => 'form-control', 'placeholder' => 'Amount', 'required' => 'required', 'style' => 'width:120px;display:inline-block']) !!} USD
                                {!! Form::errorMessage('amount') !!}
                            </div>
                            <div class="col-sm-3">
                                <button type="submit" class="btn btn-default">Pay with Paypal</button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                        @if (\Config::get('app.debug'))
                            <small>testing login adtw-buyer@ifrond.com pass 9CWa3QjjbaRk</small>
                        @endif
                    </div>
                </div>
            </section>
        </div>
    </div>

@endsection