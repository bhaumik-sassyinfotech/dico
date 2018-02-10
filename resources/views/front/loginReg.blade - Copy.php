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
                        <h3 class="page-title">LOGIN</h3>


                        <form action="{{url('/frontLogin')}}" method="post" class="form-horizontal login-wrap" id="loginForm" name="loginForm">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <div class="field">
                                    <input type="email" class="form-control" name="email" placeholder="Email Id" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="field">
                                    <input type="password" class="form-control" name="password" placeholder="Password" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="field">
                                    <div class="field-half">
                                        <a href="javascript:void(0);" onclick="showForgetForm();" class="othr-link">Forgot Password?</a>
                                    </div>
                                    <div class="field-half button-wrap">
                                        <input type="submit" class="btn btn-primary" value="SEND" name="login">                                        
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="box-changer to-register">Don’t have an account yet? <a href="javascript:void(0);" onclick="showRegisterForm();" class="link">REGISTER</a></div>
                    </div>
                    <div class="register-box chng-box">
                        <h3 class="page-title">REGISTER</h3>

                        <form action="{{url('/companyRegister')}}" method="post" class="form-horizontal register-wrap" id="registerForm" name="registerForm" enctype="multipart/form-data">
                            {{ csrf_field() }}

                            <div class="form-group">
                                <div class="field">
                                    {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => 'Full Name', 'maxlength' => '30']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="field">
                                    {!! Form::text('company_name', old('company_name'), ['class' => 'form-control', 'placeholder' => 'Company Name', 'maxlength' => '50']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="field">
                                    {!! Form::email('email', old('email'), ['class' => 'form-control', 'placeholder' => 'Email Id']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="field">
                                    <select name="package_id" id="package_id" class="form-control" >
                                        <option value="" disabled="" selected="">Select Package List</option>
                                        @foreach ($packageList as $package)
                                        <option value="{{ $package->id }}">{{ $package->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="error_package_id"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="field">
                                    <input type="text" class="form-control" name="card_number" id="card_number" maxlength="16" placeholder="Card Number" required>
                                </div>
                            </div>
                            
                             <div class="form-group tbl">
                                 <div class="tbl-cell">
                                    <input type="password" class="form-control" name="cvc" id="cvc" maxlength="4" placeholder="CVC" required>
                                </div>
                                 <div class="tbl-cell">
                                    <select name="ex_month" id="ex_month" class="form-control" >
                                        <option value="" disabled="" selected="">Select month</option>
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
                                        <option value="" disabled="" selected="">Select Year</option>
                                        <?php
                                        $Y = date('Y');
                                        for ($i = 1; $i < 15; $i++) {
                                            ?>

                                            <option value="{{ $Y }}">{{ $Y }}</option>
                                            <?php $Y++;
                                        }
                                        ?>
                                    </select>
                                    <div class="error_year"></div>
                                </div>
                            </div>
                            
                            
                            <div class="form-group">
                                <div class="field no-padding">
                                    <div class="field-half">
                                        <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
                                    </div>


                                    <div class="field-half">
                                        <input type="password" class="form-control" name="confirmPassword" id="confirmPassword" placeholder="Confirm Password" required>
                                    </div>                                
                                </div>

                            </div>
<!--                               <div class="form-group">
                                <div class="field">
                                    {!! Form::textarea('company_description', old('company_description'), ['class' => 'form-control', 'placeholder' => 'Company Description']) !!}
                                </div>
                            </div>
                         <div class="form-group">
                                <div class="field">
                                    <input name="company_logo" type="file" id="company_logo" class="form-control">
                                </div>
                                 <div class="error_company_logo"></div>
                            </div>
                            <div class="form-group">
                                <div class="field">
                                    <div class="row">
                                        <div class="col-sm-12 form-group">
                                            <img id="company_logo_img" height="100" width="100"/>
                                        </div>
                                    </div>
                                </div>
                            </div>-->
                            <div class="form-group">
                                <div class="field">
                                    <div class="text-right button-wrap">
                                        <input type="submit" class="btn btn-primary" value="REGISTER" name="REGISTER">   
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="box-changer to-login">Already have an account? <a href="javascript:void(0);" onclick="showLoginForm();" class="link">LOGIN</a></div>
                    </div>
                    <div class="forgot-box chng-box">
                        <h3 class="page-title">Forgot Your Password?</h3>
                        <form action="" class="form-horizontal forgot-wrap">
                            <div class="form-group">
                                <div class="field">
                                    <input type="email" class="form-control" name="reg-email" placeholder="Email Id" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="field">
                                    <div class="text-right button-wrap">
                                        <a href="#" class="btn btn-primary">SEND</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="box-changer to-login">Already have an account? <a href="javascript:void(0);" onclick="showLoginForm();" class="link">LOGIN</a></div>
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

    $('#company_logo').change(function (event) {
        var reader = new FileReader();
        reader.onload = function () {
            var output = document.getElementById('company_logo_img');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
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
                required: 'This field is required',
            },
            email: {
                required: 'This field is required',
                email: 'Please enter a valid email address.'
            }
        }
    });
    $("#registerForm").validate({
        rules: {
            name: {
                required: true,
                pattern: '/^[a-zA-Z\s]+$/'
            },
            company_name: {
                required: true,
                pattern: '/^[a-zA-Z\s]+$/'
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
            },
            month: {
                required: true
            },
            company_description: {
                required: true
            },
            company_logo: {
                required: true
            },
        },
        messages: {
            name: {
                required: 'This field is required',
                pattern: 'Please enter proper value'
            },
            company_name: {
                required: 'This field is required',
                pattern: 'Please enter proper value'
            },
            email: {
                required: 'This field is required',
                email: 'Please enter a valid email address.'
            },
            password: {
                required: 'This field is required',
                minlength: 'Password must be a minimum 6 characters',
            },
            confirmPassword: {
                required: 'This field is required',
                equalTo: 'Password not match'
            },
            package_id: {
                required: 'This field is required',
            },
            card_number: {
                required: 'This field is required',
                 number: 'Please enter numeric value only'
            },
            cvc: {
                required: 'This field is required',
            },
            ex_year: {
                required: 'This field is required',
            },
            ex_month: {
                required: 'This field is required',
            },
            company_description: {
                required: 'This field is required',
            },
            company_logo: {
                required: 'This field is required',
            },
        },
        errorPlacement: function (error, element) {
            if (element.attr("name") == "package_id") {
                $(".error_package_id").html(error);
            } else if (element.attr("name") == "year") {
                $(".error_year").html(error);
            } else if (element.attr("name") == "month") {
                $(".error_month").html(error);
            }else if (element.attr("name") == "company_logo") {
                $(".error_company_logo").html(error);
            } else {
                error.insertAfter(element);
            }
        },
    });
    $(document).ready(function () {
        //this is use for package price with select 2
       // $('#package_id').select2({width: 100 + '%'});
        //$('#ex_year').select2({width: 100 + '%'});
       // $('#ex_month').select2({width: 100 + '%'});
    });
</script>
@endsection