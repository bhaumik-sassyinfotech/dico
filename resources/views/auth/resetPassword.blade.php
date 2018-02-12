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
        <form class="common-form" method="POST" action="{{url('/updateForgotPassword')}}" id="updateForgotPassword">

            {{ csrf_field()            }}
            <input type="hidden" class="form-control" name="email" id="email" value="<?= $email[0]; ?>">
            <input type="hidden" class="form-control" name="usertype"  value="0">
            <input type="hidden" class="form-control" name="uid" id="uid" value="<?= $email[2]; ?>">
        <div class="form-group">
            <label class="text-15">New Password:</label>
            <input id="password" name="password" type="password" >            
        </div>

        <div class="form-group">
            <label class="text-15">Confirm Password:</label>
            <input id="confirmPassword" name="confirmPassword" type="password" >            
        </div>
        <div class="form-group">
            <div class="btn-wrap-div">                    
                <input type="submit" value="Send" class="st-btn loginBtn">
            </div>
        </div>
    </form>
</div>
</body>
<script>
     $("#updateForgotPassword").validate({
        rules: {

            password: {
                required: true,
                minlength: 6
            },
            confirmPassword: {
                required: true,
                equalTo: "#password"
            }
        },
        messages: {
            password: {
                required: 'This field is required',
                minlength: 'Password must be a minimum 6 characters',
            },
            confirmPassword: {
                required: 'This field is required',
                equalTo: 'Password not match'
            }
        }
    });
</script>
@endsection

