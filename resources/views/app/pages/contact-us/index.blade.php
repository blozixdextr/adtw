@extends('app.layouts.'.$user->type)

@section('head-style')
    <link rel="stylesheet" href="/assets/app/css/views/for-contact-us.css">
@endsection

@section('content')


    <div class="panel panel-default">
        <div class="panel-heading"><h2><i class="fa fa-envelope-o"></i> Contact Us</h2></div>
        <div class="panel-body fields-border">

            {!! Form::open(['url' => '/contact-us', 'id' => 'contactUs']) !!}

                <div class="form-group">
                    {!! Form::label('title', 'Your Name') !!}
                    {!! Form::text('title', old('title', $username), ['required' => 'required', 'placeholder' => 'Your Name', 'class' => 'form-control']) !!}
                    {!! Form::errorMessage('title') !!}
                </div>

                <div class="form-group">
                    {!! Form::label('message', 'Your Message') !!}
                    {!! Form::textarea('message', old('message', ''), ['required' => 'required', 'placeholder' => 'Your Message', 'class' => 'form-control']) !!}
                    {!! Form::errorMessage('message') !!}
                </div>

                <div class="text-center">{!! Form::submit('Send', ['class' => 'btn-white']) !!}</div>

            {!! Form::close() !!}

        </div>
    </div>

@endsection
