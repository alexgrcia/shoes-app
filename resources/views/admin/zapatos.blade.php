@extends('adminlte::page')

@section('title', 'Zapatos')

@section('content_header')
    <h1>Listado de Zapatos</h1>
@stop

@section('content')
    <div id="app">
        <zapato-component /> 
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