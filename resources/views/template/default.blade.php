<!DOCTYPE html>
<html lang="en">
<head>   
@include('template.head')
</head>
<body class="">
@include('template.header')
<div id="page-container">
@include('template.sidebar') 
@yield('content')
</div>
</body>
@include('template.footer')
@stack('javascripts')
</html>