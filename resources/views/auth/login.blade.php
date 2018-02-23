@extends('layouts.login')
<title>@lang('label.adDICO - Login')</title>    
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
        {{--<p>Company: <span>Agency 96</span></p>--}}
        <form class="common-form" method="POST" action="{{ route('login') }}" id="login_form">
            {{ csrf_field() }}
            <div class="form-group">
                <label class="text-15">@lang('label.adEmail Id'):</label>
                <input id="email" name="email" type="email" placeholder="@lang('label.adEmail Id')"  autofocus>
                <div id="email_error" class="error1"></div>
            </div>
            <div class="form-group">
                <label class="text-15">@lang('label.adPassword'):</label>
                <input name="password" id="password" type="password" placeholder="@lang('label.adPassword')" >
                <div id="password_error" class="error1"></div>
            </div>
            <div class="form-group">
                <div class="btn-wrap-div">
                    <label class="check">@lang('label.adRemember Me')<input type="checkbox" {{ old('remember') ? 'checked' : '' }}>
                        <span class="checkmark"></span>
                    </label>
                    <a href="{{route('adminForgotPassword')}}">@lang('label.adForgot Password')</a>
                    <input type="submit" value="Login" class="st-btn loginBtn">
                </div>
            </div>
        </form>
    </div>
    </body>
@endsection

