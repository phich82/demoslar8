@extends('layouts.master')

@section('page_title')
{{$page_title}}
@endsection

@section('content')
<div class="container">
    <div>{{$message}}</div>
    <div>{{$status}}</div>
    <div>
        <a href="{{$goto ?? route('home')}}">Go back</a>
    </div>
</div>
@endsection
