@extends('app.layouts.twitcher')

@section('head-style')
    <link rel="stylesheet" href="/assets/app/libs/color-picker/css/bootstrap-colorpicker.min.css" />
@endsection

@section('head-js')
    <script src="/assets/app/libs/color-picker/js/bootstrap-colorpicker.min.js"></script>
    <script>
        $(function(){
            $('#bgColorPicker').colorpicker();
            $('#startPopup').click(function(e){
                e.preventDefault();
                var color = $('#bgColor').val();
                color = color.replace('#', '');
                var url = $(this).attr('href') + '?color=' + color;
                var popup = window.open(url, 'name', 'width={{ $requiredSizes[0] }},height={{ $requiredSizes[1] }}');
                if (window.focus) {
                    popup.focus();
                }
            });
        });
    </script>
@endsection

@section('content')
    <h1><i class="fa fa-file-image-o"></i> Banner {{ $bannerType->title }}</h1>
    @foreach($banners as $b)
        <img src="{{ $b->file }}">
    @endforeach
    <div class="clearfix"></div>
    <h2><i class="fa fa-play"></i> Start the stream</h2>
    <div class="stream-color fields-border">
        <div class="input-group col-xs-3" id="bgColorPicker">
            <input type="text" value="#00ff00" class="form-control"  id="bgColor" />
            <span class="input-group-addon"><i></i></span>
        </div>
        <a href="/user/twitcher/banner/popup/{{ $bannerType->id }}" class="btn-white col-xs-3" id="startPopup">start show now</a>
    </div>
    <div class="height100"></div>
    <div class="col-xs-6 help-window">
        <a href="#" onclick="toggle_visibility('foo');">Read help <i class="fa fa-question-circle"></i></a>
        <div class="panel panel-default panel-body" id="foo">This is foo This is foo  This is foo This is foo This is foo This is foo This is foo This is foo This is foo This is foo This is foo This is foo This is foo This is foo This is foo This is foo This is foo This is foo</div>
    </div>
@endsection
    

