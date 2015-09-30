<!doctype html>
<html>
<head>
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Contact Us</title>
</head>

<body bgcolor="#f6f6f6">

<p>Hi there,</p>
<p>You got a message from adtw.ch</p>
<h1>Contact Us</h1>
<blockquote>{!! $text !!}</blockquote>
<h2>User info</h2>
@if ($user)
    <a href="{{ url('/admin/user/'.$user->id) }}">{{ $title }}</a>
@else
    {{ $title }}
@endif

</body>
</html>