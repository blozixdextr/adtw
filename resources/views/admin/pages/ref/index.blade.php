@extends('admin.layouts.index')

@section('content')

    <h1>Ref Types</h1>
    <ul>
        @forelse($refTypes as $r)
            <li><a href="/admin/ref/type/{{ $r->type }}">{{ $r->type }}</a></li>
        @empty
            <li><em>no ref types</em></li>
        @endforelse
    </ul>

@endsection