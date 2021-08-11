<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="url-local" content="{{ url('') }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="{{ asset('img/favicon.ico') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}"/>
    <title>Autentique</title>
    @section('css')
    @show
</head>
<body>
@section('content')
@show
<script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
@section('js')
@show
</body>
</html>
