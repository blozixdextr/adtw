@extends('admin.layouts.index')

@section('content')

    <h1>{{ $type }}</h1>
    <ul>
        @forelse($refs as $r)
            <li><a href="/admin/ref/{{ $r->id }}/show">{{ $r->title }}</a></li>
        @empty
            <li><em>no ref types</em></li>
        @endforelse
    </ul>

@endsection