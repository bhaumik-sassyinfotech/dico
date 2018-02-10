@extends('front_template.layout')
@section('content')    
<!-- ************* content-Section ********************-->
<div class="content-wrapper">
    <div class="wrap login-register-wrap">
        <div class="container">
            <div class="log-reg-section">
                <div class="log-reg-cell left">
                    <div class="image">
                        <img src="{{asset('public/front/images/login-group.png')}}" alt="">
                    </div>
                </div>
                <div class="log-reg-cell right">
                    @if(session()->has('success'))
                    <div class="alert alert-success wrap">
                        {{ session()->get('success') }}
                    </div>
                    @endif
                    @if(session()->has('err_msg'))
                    <div class="alert alert-danger wrap">
                        {{ session()->get('err_msg') }}
                    </div>
                    @endif
                    @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <div class="login-box chng-box">
                        <h3 class="page-title">Reset Password</h3>
                        <form action="{{url('/updateForgotPassword')}}" method="post" class="form-horizontal login-wrap" id="updateForgotPassword" name="updateForgotPassword">
                            {{ csrf_field() }}
                            <input type="hidden" class="form-control" name="email" id="email" value="<?=$email[0];?>">
                            <input type="hidden" class="form-control" name="uid" id="uid" value="<?=$email[2];?>">
                            <input type="hidden" class="form-control" name="usertype"  value="1">
                            <div class="form-group">                                
                                <div class="field">
                                    <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="field">
                                    <input type="password" class="form-control" name="confirmPassword" id="confirmPassword" placeholder="Confirm Password" required>
                                </div>                                
                            </div>

                    <div class="form-group">
                        <div class="field">
                            <div class="text-left button-wrap">
                                <input type="submit" class="btn btn-primary" value="SEND" name="SEND">   
                            </div>
                        </div>
                    </div>
                    </form>   
                  
                </div>
                
            </div>
        </div>
    </div>
</div>
</div>
<!-- ************* content-Section close********************-->

@stop
@section('javascript')
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
    $(document).ready(function () {
        $('.alert-success').delay(5000).fadeOut('slow');
        $('.alert-danger').delay(5000).fadeOut('slow');
        $('.alert-warning').delay(5000).fadeOut('slow');
    });
</script>
@endsection