@extends('front_template.layout')
@section('content')    

<!-- ************* content-Section ********************-->
<div class="content-wrapper">
    <div class="wrap contact-wrapper">
        <div class="container">
            <h3 class="page-title">Contact Us</h3>
            <div class="contact-wrap">
                <div class="wrap-cell">
                    <div class="top-section">
                        <div class="img-wrp">
                            <img src="{{asset('public/front/images/ic-call.png')}}" alt="">
                        </div>
                        <div class="text">PHONE</div>
                    </div>
                    <div class="bottom-section">
                        <p><a href="tel:{{Helpers::getSettings('app_features')->mobile}}">{{Helpers::getSettings('app_features')->mobile}}</a></p>
                        <p><a href="tel:{{Helpers::getSettings('app_features')->phone}}">{{Helpers::getSettings('app_features')->phone}}</a></p>
                        
                    </div>
                </div>
                <div class="wrap-cell">
                    <div class="top-section">
                        <div class="img-wrp">
                            <img src="{{asset('public/front/images/ic-location.png')}}" alt="">
                        </div>
                        <div class="text">ADDRESS</div>
                    </div>
                    <div class="bottom-section">
                        <p><?=Helpers::getSettings('app_features')->address;?></p>
                    </div>
                </div>
                <div class="wrap-cell">
                    <div class="top-section">
                        <div class="img-wrp">
                            <img src="{{asset('public/front/images/ic-envelop.png')}}" alt="">
                        </div>
                        <div class="text">EMAIL</div>
                    </div>
                    <div class="bottom-section">
                        <p><a href="mailto:{{Helpers::getSettings('app_features')->email1}}">{{Helpers::getSettings('app_features')->email1}}</a></p>
                        <p><a href="mailto:{{Helpers::getSettings('app_features')->support_email}}">{{Helpers::getSettings('app_features')->support_email}}</a></p>
                    </div>
                </div>
            </div>
            <div class="inquiry-wrap">
                <div class="title">We’d love to hear from you</div>
                <div class="wrap inquiry-group">
                    <div class="wrap-50">
                        <form action="" class="form-horizontal" id="clientContactUs" method="POST">
                            <div class="form-group">
                                <div class="field">
                                    <input type="text" class="form-control" name="name" id="name" placeholder="Full Name" >
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="field">
                                    <input type="email" class="form-control" name="email" id="email" placeholder="Email Id" >
                                    <div class="email_error"></div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="field">
                                    <input type="number" class="form-control" name="mobile" id="mobile" placeholder="Phone Number" >
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="field">
                                    <textarea class="form-control" name="message" id="message" placeholder="Message"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="field">
                                    <div class="text-center button-wrap">
<!--                                        <a href="#" class="btn btn-primary">SEND</a>-->
                                        <input type="submit" class="btn btn-primary" name="SEND" value="SEND" >                                
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="wrap-50">
                        <div class="img-wrap">
                            <img src="{{asset('public/front/images/contact-env-box.png')}}" alt="">
                        </div>
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
    $("#clientContactUs").validate({
        rules: {
            name: {
                required: true,
            },
            mobile: {
                required: true,
            },
            email: {
                required: true,
                email: true
            },
            message: {
                required: true,
            }
        },
        messages: {
            name: {
                required: 'This field is required',
            },
            mobile: {
                required: 'This field is required',
            },
            email: {
                required: 'This field is required',
                email: 'Please enter a valid email address.'
            },
            message: {
                required: 'This field is required',
            }
        },
        errorPlacement: function (error, element) {
            if (element.attr("name") == "email") {
                $(".email_error").html(error);
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function (form) {
            var name = $('#name').val();
            var email = $('#email').val();
            var mobile = $('#mobile').val();
            var message = $('#message').val();
            // var _token '= <?php csrf_field(); ?>';
            var _token = CSRF_TOKEN;
            var url = "{{ URL::to('/faqsEmail')}}";
            $('#loader').show();
            $.ajax({
                url: url,
                method: "POST",
                data: {name: name, email: email,mobile:mobile, message: message, _token: _token},
                success: function (data) {
                    obj = jQuery.parseJSON(data);
                    $('#loader').hide();
                    if (obj.status == 1)
                    {
                        swal("Email Sent!", obj.msg, "success");
                    } else {
                        swal("Error!", obj.msg, "error");
                    }
                    $("#clientContactUs")[0].reset();
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    $('#loader').hide();

                }
            });
        }
    });
</script>
@endsection