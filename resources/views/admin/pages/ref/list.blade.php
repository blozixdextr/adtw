@extends('admin.layouts.index')

@section('content')
    <h1>{{ ucfirst($type) }}</h1>
    <a href="/admin/ref/{{ $type }}/create" class="btn btn-lg btn-success">Add ref here</a>
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
        @forelse($refs as $r)
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