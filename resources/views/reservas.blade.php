@extends('layouts.app')

@section('title', 'Categorias')

@section('content_header')
    <h1>Listado de categorias</h1>
@stop

@section('content')
    <div id="app">
        <reservas-cliente :user="{{Auth::user() !=null ?  Auth::user() : json_encode($user = array())}}" /> 
    </div>
@stop

@section('css') 
    <!--{{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}-->
    @vite('resources/css/app.css')
@stop

@section('js')
    <!--<script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>-->
    @vite('resources/js/app.js')
@stop