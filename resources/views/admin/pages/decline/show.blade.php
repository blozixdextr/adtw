@extends('admin.layouts.index')

@section('content')
    <h1>Decline</h1>
    <p><a href="/admin/decline/{{ $bannerStream->id }}/stream" class="btn btn-info">Stream {{ $bannerStream->stream->created_at->format('d.m.Y') }}</a></p>
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-danger">
                <div class="panel-heading">Client says</div>
                <div class="panel-body">
                    {{ nl2br($bannerStream->client_comment) }}
                </div>
            </div>
            <a href="/admin/decline/{{ $bannerStream->id }}/client" class="btn btn-danger">I agree with client</a>
        </div>
        <div class="col-md-6">
            <div class="panel panel-success">
                <div class="panel-heading">Streamer says</div>
                <div class="panel-body">
                    {{ nl2br($bannerStream->twitcher_comment) }}
                </div>
            </div>
            <a href="/admin/decline/{{ $bannerStream->id }}/streamer" class="btn btn-success">I agree with streamer</a>
        </div>
    </div>

@endsection