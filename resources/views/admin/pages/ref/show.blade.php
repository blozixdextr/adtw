@extends('admin.layouts.index')

@section('content')
    <h1>{{ ucfirst($ref->title) }} / <small>{{ $ref->type }}</small></h1>
    <a href="/admin/ref/{{ $ref->id }}/edit" class="btn btn-primary">Edit</a>
    @if ($canHaveChildren)
        <a href="/admin/ref/{{ $ref->type }}/create?pid={{ $ref->id }}" class="btn btn-success">Add child</a>
    @endif

    <h2>Children</h2>
    <table class="table table-striped">
        <thead>
        <tr>
            <td>#</td>
            <td>title</td>
            <td>childs</td>
            <td>actions</td>
        </tr>
        </thead>
        <tbody>
        @forelse($ref->children as $r)
            <tr>
                <td>{{ $r->id }}</td>
                <td>{{ $r->title }}</td>
                <td>{{ $r->children()->count() }}</td>
                <td>
                    <a href="/admin/ref/{{ $r->id }}/show" class="btn btn-xs btn-info">show</a>
                    <a href="/admin/ref/{{ $r->id }}/edit" class="btn btn-xs btn-primary">edit</a>
                    <a href="/admin/ref/{{ $r->id }}/remove" class="btn btn-xs btn-danger confirm-delete">remove</a>
                </td>
            </tr>
        @empty
            <tr><td colspan="4"><em>no refs of this types</em></td></tr>
        @endforelse
        </tbody>

    </table>

@endsection