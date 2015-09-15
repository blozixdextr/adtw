@extends('admin.layouts.index')

@section('content')

    <section class="content-header">
        <h1>
            Locations
        </h1>
    </section>

    @if ($location)
        <h2>
            {{ $location->title }}
            <small>{{ $location->type }}</small>
            <a class="btn btn-primary" href="/admin/location/edit/{{ $location->id }}">edit</a>
            <a class="btn btn-success" href="/admin/location/create?id={{ $location->id }}">add child</a></h2>
    @else
        <h2>States <a class="btn btn-success" href="/admin/category/create">add</a></h2>
    @endif
    <table class="table-striped table ">
        <thead>
            <tr>
                <th>#</th>
                <th>type</th>
                <th>title</th>
                <th>childs</th>
                <th>actions</th>
            </tr>
        </thead>
    @forelse($locations as $l)
        <tr>
            <td>{{ $l->id }}</td>
            <td>{{ $l->type }}</td>
            <td>
                <a href="/admin/location/show/{{ $l->id }}">{{ $l->title }}</a>
            </td>
            <td>
                @if ($l->type == 'state')
                    <a class="btn btn-primary btn-xs" href="/admin/location?id={{ $l->id }}">{{ $l->children()->count() }} regions</a>
                @elseif ($l->type == 'region')
                    <a class="btn btn-primary btn-xs" href="/admin/location?id={{ $l->id }}">{{ $l->children()->count() }} cities</a>
                @else
                    -
                @endif
            </td>
            <td>
                @if ($l->children()->count() == 0)
                    <a class="btn btn-danger btn-xs confirm-delete" href="/admin/location/destroy/{{ $l->id }}">delete</a>
                @endif
                <a class="btn btn-primary btn-xs" href="/admin/location/edit/{{ $l->id }}">edit</a>
            </td>
        </tr>
    @empty
        <tr><td colspan="5">no locations yet</td></tr>
    @endforelse
    </table>

    @endsection