@extends('admin.layouts.index')

@section('content')

    <section class="content-header">
        <h1>Category Details</h1>
        <ol class="breadcrumb">
            <li><a href="/admin/category"><i class="fa fa-dashboard"></i> Categories</a></li>
            <li class="active">show</li>
        </ol>
</section>

    <h1>{{ $category->title }} <a href="/admin/category/edit/{{ $category->id }}" class="btn btn-primary">edit</a></h1>

    <div class="panel panel-primary">
        <div class="panel-heading">
            Icon
        </div>
        <div class="panel-body">
        @if ($category->icon)
                <img src="{{ \App\Models\Mappers\CategoryMapper::getIconSrc($category) }}">
        @else
            <em>no icon yet</em>
        @endif
        </div>
    </div>

    <div class="panel panel-primary">
        <div class="panel-heading">
            Image
        </div>
        <div class="panel-body">
            @if ($category->image)
                <img src="{{ \App\Models\Mappers\CategoryMapper::getImageSrc($category) }}">
            @else
                <em>no icon yet</em>
            @endif
        </div>
    </div>

    <div class="panel panel-primary">
        <div class="panel-heading">
            Description
        </div>
        <div class="panel-body">
            {!! $category->subtitle !!}
        </div>
    </div>

    <div class="panel panel-primary">
        <div class="panel-heading">
            SEO titles
        </div>
        <div class="panel-body">
            <p>
                <strong>en</strong><br>
                {!! trans('categories.'.$category->title.'.seo.title', [], 'messages', 'en') !!}
            </p>
            <p>
                <strong>es</strong><br>
                {!! trans('categories.'.$category->title.'.seo.title', [], 'messages', 'es') !!}
            </p>
            <p>
                <strong>ru</strong><br>
                {!! trans('categories.'.$category->title.'.seo.title', [], 'messages', 'ru') !!}
            </p>
        </div>
    </div>

    <div class="panel panel-primary">
        <div class="panel-heading">
            SEO keywords (in language files)
        </div>
        <div class="panel-body">
            <p>
                <strong>en</strong><br>
                {!! trans('categories.'.$category->title.'.seo.keywords', [], 'messages', 'en') !!}
            </p>
            <p>
                <strong>es</strong><br>
                {!! trans('categories.'.$category->title.'.seo.keywords', [], 'messages', 'es') !!}
            </p>
            <p>
                <strong>ru</strong><br>
                {!! trans('categories.'.$category->title.'.seo.keywords', [], 'messages', 'ru') !!}
            </p>
        </div>
    </div>

    <div class="panel panel-primary">
        <div class="panel-heading">
            SEO description
        </div>
        <div class="panel-body">
            <p>
                <strong>en</strong><br>
                {!! trans('categories.'.$category->title.'.seo.description', [], 'messages', 'en') !!}
            </p>
            <p>
                <strong>es</strong><br>
                {!! trans('categories.'.$category->title.'.seo.description', [], 'messages', 'es') !!}
            </p>
            <p>
                <strong>ru</strong><br>
                {!! trans('categories.'.$category->title.'.seo.description', [], 'messages', 'ru') !!}
            </p>
        </div>
    </div>

@endsection