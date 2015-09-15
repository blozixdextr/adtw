@extends('admin.layouts.index')

@section('content')

    <section class="content-header">
        <h1>Location Details</h1>
        <ol class="breadcrumb">
            <li><a href="/admin/location"><i class="fa fa-dashboard"></i> Locations</a></li>
            <li class="active">show</li>
        </ol>
    </section>

    <h1>{{ $location->title }} <a href="/admin/location/edit/{{ $location->id }}" class="btn btn-primary">edit</a></h1>

    <div class="panel panel-primary">
        <div class="panel-heading">
            Description
        </div>
        <div class="panel-body">
            {!! $location->subtitle !!}
        </div>
    </div>

@endsection