@extends('layouts.master')

@section('content')
    <div class="my-4">
        @livewire('post-edit', ['post'=> $post])
    </div>
@endsection
