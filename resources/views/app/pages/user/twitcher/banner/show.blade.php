@extends('app.layouts.twitcher')

@section('head-style')
    <link rel="stylesheet" href="/assets/app/libs/color-picker/css/bootstrap-colorpicker.min.css" />
    <style>
        .colorpicker.colorpicker-visible {
            bottom: auto;
        }
    </style>
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
                var popup = window.open(url, 'name', 'height=620,width=820');
                if (window.focus) {
                    popup.focus();
                }
            });
        });
    </script>
@endsection

@section('content')
    <h1>Banner {{ $bannerType->title }}</h1>
    @foreach($banners as $b)
        <img src="{{ $b->file }}">
    @endforeach
    <div class="clearfix"></div>
    <h2>Start the stream</h2>
    <div class="input-group" id="bgColorPicker">
        <input type="text" value="#00ff00" class="form-control"  id="bgColor" />
        <span class="input-group-addon"><i></i></span>
    </div>
    <a href="/user/twitcher/banner/popup/{{ $bannerType->id }}" class="btn btn-default btn-lg" id="startPopup">start show now</a>
@endsection

