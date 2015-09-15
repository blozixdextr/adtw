@extends('admin.layouts.index')

@section('content')

    <section class="content-header">
        <h1>
            Categories
        </h1>
    </section>

    @if ($category)
        <h2>{{ $category->title }}
            <a class="btn btn-primary" href="/admin/category/edit/{{ $category->id }}">edit</a>
            <a class="btn btn-success" href="/admin/category/create?id={{ $category->id }}">add child</a></h2>
    @else
        <h2>Main categories <a class="btn btn-success" href="/admin/category/create">add</a></h2>
    @endif
    <table class="table-striped table ">
        <thead>
            <tr>
                <th>#</th>
                <th>title</th>
                <th>url</th>
                <th>childs</th>
                <th>actions</th>
            </tr>
        </thead>
    @forelse($categories as $c)
        <tr>
            <td>{{ $c->id }}</td>
            <td><a href="/admin/category/show/{{ $c->id }}">{{ $c->title }}</a></td>
            <td><a class="btn btn-default" href="{{ $c->url() }}">{{ $c->url() }}</a></td>
            <td>
                @if ($c->type == 'category')
                    <a class="btn btn-primary btn-xs" href="/admin/category?id={{ $c->id }}">{{ $c->children()->count() }} childs</a>
                @else
                    -
                @endif
            </td>
            <td>
                @if ($c->children()->count() == 0)
                    <a class="btn btn-danger btn-xs confirm-delete" href="/admin/category/destroy/{{ $c->id }}">delete</a>
                @endif
                <a class="btn btn-primary btn-xs" href="/admin/category/edit/{{ $c->id }}">edit</a>
            </td>
        </tr>
    @empty
        <tr><td colspan="5">no categories yet</td></tr>
    @endforelse
    </table>

    @endsection