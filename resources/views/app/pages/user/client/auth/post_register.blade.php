@extends('app.layouts.index')

@section('head-style')
    <link rel="stylesheet" href="/assets/app/css/views/for-main.css">
    <link rel="stylesheet" href="/assets/app/css/shared/modal.css">
    <style>
        .intro-main {
            font-size: 14px;
        }

        h1 {
          margin: 25px 0;
        }
    </style>
@endsection

@section('content')
    <div class="text-center intro-main welcome">
        <h1>Finish your registration</h1>
        <p>We sent you a email to {{ $user->email }}. Use link in email to finish your registration</p>
    </div>
@endsection
