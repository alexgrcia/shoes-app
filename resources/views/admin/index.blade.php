@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard Administrativo</h1>
@stop

@section('content')
    <p>Welcome to this beautiful admin panel.</p>
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