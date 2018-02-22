@extends('front_template.layout')
@section('content')    
<!-- ************* content-Section ********************-->
<style>
    .hidden{
            display: none;
    }    
</style>
<?php 
    $language = App::getLocale();
    $pkg_id = '0';
    if(isset($package) && $package != null)
    {
        $pkg_id =$package;
    }    
?>
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
                        <h3 class="page-title">@lang('label.LOGIN')</h3>
                        <form action="{{url($language.'/frontLogin')}}" method="post" class="form-horizontal login-wrap" id="loginForm" name="loginForm">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <div class="field">
                                    <input type="email" class="form-control" name="email" placeholder="@lang('label.Email_Id')" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="field">
                                    <input type="password" class="form-control" name="password" placeholder="@lang('label.Password')" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="field">
                                    <div class="field-half">
                                        <a href="javascript:void(0);" onclick="showForgetForm();" class="othr-link">@lang('label.Forgot Password?')</a>
                                    </div>
                                    <div class="field-half button-wrap">
                                        <input type="submit" class="btn btn-primary" value="@lang('label.SEND')" name="login">                                        
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="box-changer to-register">@lang('label.Dont have an account yet?') <a href="javascript:void(0);" onclick="showRegisterForm();" class="link">@lang('label.REGISTER')</a></div>
                    </div>
                    <div class="register-box chng-box">
                        <h3 class="page-title">@lang('label.REGISTER')</h3>

                        <form action="{{url($language.'/companyRegister')}}" method="post" class="form-horizontal register-wrap" id="registerForm" name="registerForm" enctype="multipart/form-data">
                            {{ csrf_field() }}

                            <div class="form-group">
                                <div class="field">
                                    {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => __("label.Full Name"), 'maxlength' => '20']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="field">
                                    {!! Form::text('company_name', old('company_name'), ['class' => 'form-control', 'placeholder' => __("label.Company Name"), 'maxlength' => '30']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="field">
                                    {!! Form::email('email', old('email'), ['class' => 'form-control', 'placeholder' =>  __("label.Email_Id")]) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="field">
                                    <select name="package_id" id="package_id" class="form-control" onchange="packageType()">
                                        <option value="" disabled="" selected="" >@lang('label.Select Package List')</option>
                                        @foreach ($packageList as $package)
                                        <option value="{{ $package->id }}" <?php if($pkg_id !='0' && $pkg_id == $package->slug_name ){echo "selected";} ?> >{{ $package->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="error_package_id"></div>
                                </div>
                            </div>
                            <div class="my_package">
                                <div class="form-group">
                                    <div class="field">
                                        <input type="text" class="form-control" name="card_number" id="card_number" maxlength="16" placeholder="@lang('label.Card Number')" required>
                                    </div>
                                </div>

                                <div class="form-group tbl">
                                    <div class="tbl-cell">
                                        <input type="password" class="form-control" name="cvc" id="cvc" maxlength="4" placeholder="@lang('label.CVC')" required>
                                    </div>
                                    <div class="tbl-cell">
                                        <select name="ex_month" id="ex_month" class="form-control" >
                                            <option value="" disabled="" selected="">@lang('label.Select month')</option>
                                            <?php
                                            for ($i = 1; $i < 12; $i++) {
                                                ?>
                                                <option value="{{ $i }}">{{ $i }}</option>
                                            <?php } ?>
                                        </select>
                                        <div class="error_month"></div>
                                    </div>
                                    <div class="tbl-cell">
                                        <select name="ex_year" id="ex_year" class="form-control" >
                                            <option value="" disabled="" selected="">@lang('label.Select Year')</option>
                                            <?php
                                            $Y = date('Y');
                                            for ($i = 1; $i < 15; $i++) {
                                                ?>

                                                <option value="{{ $Y }}">{{ $Y }}</option>
                                                <?php
                                                $Y++;
                                            }
                                            ?>
                                        </select>
                                        <div class="error_year"></div>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="field no-padding">
                                    <div class="field-half">
                                        <input type="password" class="form-control" name="password" id="password" placeholder="@lang('label.Password')" maxlength="15" required>
                                    </div>


                                    <div class="field-half">
                                        <input type="password" class="form-control" name="confirmPassword" id="confirmPassword" placeholder="@lang('label.Confirm Password')" maxlength="15" required>
                                    </div>                                
                                </div>

                            </div>

                            <div class="form-group">
                                <div class="field">
                                    <div class="text-right button-wrap">
                                        <input type="submit" class="btn btn-primary" value="@lang('label.REGISTER')" name="REGISTER">   
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="box-changer to-login">@lang('label.Already have an account?') <a href="javascript:void(0);" onclick="showLoginForm();" class="link">@lang('label.LOGIN')</a></div>
                    </div>
                    <div class="forgot-box chng-box">
                        <h3 class="page-title">@lang('label.Forgot Your Password?')</h3>
                        <form action="{{url($language.'/forgotPasswordMail')}}" method="post" class="form-horizontal login-wrap" id="forgotForm" name="forgotForm">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <div class="field">
                                    <input type="hidden" name="usertype" value="1">
                                    <input type="email" class="form-control" name="email" id="email" placeholder="@lang('label.Email_Id')" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="field">
                                    <div class="text-right button-wrap">
                                        <input type="submit" class="btn btn-primary" value="@lang('label.SEND')" name="SEND">   
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="box-changer to-login">@lang('label.Already have an account?') <a href="javascript:void(0);" onclick="showLoginForm();" class="link">@lang('label.LOGIN')</a></div>
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
    $("#forgotForm").validate({
        rules: {
            email: {
                required: true,
                email: true
            }
        },
        messages: {
            email: {
                required: '@lang("label.This field is required")',
                email:'@lang("label.Please enter a valid email address")',
            }
        }
    });
    $("#loginForm").validate({
        rules: {
            password: {
                required: true,
            },
            email: {
                required: true,
                email: true
            }
        },
        messages: {
            password: {
                required: '@lang("label.This field is required")',
            },
            email: {
                required: '@lang("label.This field is required")',
                email:'@lang("label.Please enter a valid email address")',
            }
        }
    });
    $("#registerForm").validate({
        rules: {
            name: {
                required: true,
                pattern: /^[a-zA-Z\s]*$/
            },
            company_name: {
                required: true,
                pattern: /^[a-zA-Z\s]*$/
            },
            email: {
                required: true,
                email: true
            },
            password: {
                required: true,
                minlength: 6
            },
            confirmPassword: {
                required: true,
                equalTo: "#password"
            },
            package_id: {
                required: true
            },
            card_number: {
                required: true,
                number: true
            },
            ex_year: {
                required: true
            },
            ex_month: {
                required: true
            },
            cvc: {
                required: true
            }
        },
        messages: {
            name: {
                required: '@lang("label.This field is required")',
                pattern: '@lang("label.Please enter proper value")'
            },
            company_name: {
                required: '@lang("label.This field is required")',
                pattern: '@lang("label.Please enter proper value")'
            },
            email: {
                required: '@lang("label.This field is required")',
                email:'@lang("label.Please enter a valid email address")',
            },
            password: {
                required: '@lang("label.This field is required")',
                minlength: '@lang("label.Password must be a minimum characters")',
            },
            confirmPassword: {
                required: '@lang("label.This field is required")',
                equalTo: '@lang("label.Password not match")',
            },
            package_id: {
                required: '@lang("label.This field is required")',
            },
            card_number: {
                required: '@lang("label.This field is required")',
                number: '@lang("label.Please enter numeric value only")',
            },
            cvc: {
                required: '@lang("label.This field is required")',
            },
            ex_year: {
                required: '@lang("label.This field is required")',
            },
            ex_month: {
                required: '@lang("label.This field is required")',
            },

        },
        errorPlacement: function (error, element) {
            if (element.attr("name") == "package_id") {
                $(".error_package_id").html(error);
            } else if (element.attr("name") == "year") {
                $(".error_year").html(error);
            } else if (element.attr("name") == "month") {
                $(".error_month").html(error);
            } else {
                error.insertAfter(element);
            }
        }, submitHandler: function (form) {
            var currentYear = (new Date).getFullYear();
            var currentMonth = (new Date).getMonth() + 1;
            var ex_month = $('#ex_month').val();
            var ex_year = $('#ex_year').val();
            if (ex_year == currentYear)
            {
                if (currentMonth > ex_month) {
                    swal('Error', '@lang("label.Please select valid expiry month year")', 'error')
                    return false;
                }
            }
            // return false;
            form.submit();
        }
    });
    $(document).ready(function () {
        $('.alert-success').delay(5000).fadeOut('slow');
        $('.alert-danger').delay(5000).fadeOut('slow');
        $('.alert-warning').delay(5000).fadeOut('slow');
        <?php if($pkg_id != '0'){ ?> showRegisterForm() <?php } ?>
    });
    function packageType()
    {
        var pk_id = $('#package_id').val();
        if (pk_id == 1) {
            $('.my_package').addClass('hidden');
        }else{
            $('.my_package').removeClass('hidden');
        }
    }
     <?php if($pkg_id == '0'){ ?>
   /*function showRegisterForm(){
    $('.login-box').slideUp('300',function(){
        $('.register-box').slideDown('300');
        $('.forgot-box').slideUp('300');
    });
    $('.error').removeClass('alert alert-danger').html('');

}*/
    <?php } ?>
</script>
@endsection