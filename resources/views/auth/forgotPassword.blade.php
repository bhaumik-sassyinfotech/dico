@extends('layouts.login')
<title>DICO - Login</title>    
@section('content')
    <style type="text/css">
        .error1{
            position: relative;
            left: 145px;
        }
    </style>
    <body class="login-page">
    <header class="navbar navbar-inverse navbar-fixed-top" role="banner">
        <a data-original-title="Toggle Sidebar" id="leftmenu-trigger" class="tooltips" data-toggle="tooltip" data-placement="right" title=""></a>
        <div class="navbar-header pull-left">
            <a class="navbar-brand" href="{{ url('/') }}">DICO</a>
        </div>
    </header>
    <div class="verticalcenter">
        @include('template.notification')
        {{--<p>Company: <span>Agency 96</span></p>--}}
        <form class="common-form" method="POST" action="{{url('/forgotPasswordMail')}}" id="forgot_form">
            {{ csrf_field() }}
            <div class="form-group">
                <label class="text-15">Email Id:</label>
                <input type="hidden" name="usertype" value="0">
                <input id="email" name="email" type="email" placeholder="Enter email address" required autofocus>
                <div id="email_error" class="error1"></div>
            </div>
           
            <div class="form-group">
                <div class="btn-wrap-div">                    
                    <input type="submit" value="Send" class="st-btn loginBtn">
                    <a href="{{url('/login')}}" class="st-btn loginBtn">Back</a>
                </div>
                
            </div>
        </form>
    </div>
    </body>
@endsection

