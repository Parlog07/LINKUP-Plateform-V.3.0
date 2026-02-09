{{-- resources/views/messages/show.blade.php --}}

@extends('layouts.app')

@section('content')
    <livewire:messages-page :user="$user" />
@endsection



