<!DOCTYPE html>
<html lang="en">
<title>DICO - Home</title>
<head>   
@include('template.head')
</head>
<body class="">
@include('template.header')
<div id="page-container">
@include('template.sidebar') 

@if(session()->has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
        </div>
    @endif
    @if(session()->has('err_msg'))
        <div class="alert alert-danger">
            {{ session()->get('err_msg') }}
        </div>
    @endif
    @if(session()->has('warning'))
    <div class="alert alert-warning">
        {{ session()->get('warning') }}
    </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
<div id="success_msg" class="alert alert-success" style="display: none;"></div>
<div id="error_msg" class="alert alert-danger" style="display: none;"></div>
<div id="spinner">
    <div class="spinner-img">
        <img alt="Opportunities Preloader" src="{{asset(DEFAULT_LOADER_IMAGE)}}" width="150" />
        <h2>Please Wait.....</h2>
    </div>
</div>
@yield('content')
</div>
</body>
@include('template.footer')
@stack('javascripts')
</html>