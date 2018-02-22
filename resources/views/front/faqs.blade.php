@extends('front_template.layout')
@section('content')    
<?php 
    $language = App::getLocale();
?>

<!-- ************* content-Section ********************-->
<div class="content-wrapper">
    <div class="wrap faq-wrapper">
        <div class="container">
            <h3 class="page-title">@lang('label.FAQs')</h3>
            <div class="top-block-img">
                <div class="img">
                    <img src="{{asset('public/front/images/faq-top.png')}}" alt="">
                </div>
            </div>
            @foreach ($faqsList as $faqs)
            <div class="accordion-wrapper">
                <div class="plus-minus-toggle">
                    <a href="javascript:void(0);" class="toggler">
                        <?php
                         if (App::isLocale('sp'))
                            echo $faqs->spquestion;
                        else
                            echo $faqs->question;
                        ?>
                        
                    </a>
                    <div class="toggle-icon"></div>
                </div>
                <div class="content-section">
                      <?php
                         if (App::isLocale('sp'))
                            echo strip_tags($faqs->spanswer);
                        else
                            echo strip_tags($faqs->answer);
                        ?>
                   
                </div>
            </div>
            @endforeach  
            <div class="wrap">
                <div class="flex-seprator">
                    <hr>
                </div>
            </div>

            <div class="askus-wrapper">
                <div class="title">@lang('label.Dont see your question? Please ask us here')</div>
                <form action="" class="form-horizontal" id="clientFaqs" method="POST">

                    <div class="form-group">
                        <div class="field field-half-wrap">
                            <div class="field-half">
<!--                                <input type="hidden" class="form-control" name="_token" id="_token" value="">-->
                                <input type="text" class="form-control" name="name" maxlength="30" minlength="3" id="name" placeholder="@lang('label.Full Name')" required>
                            </div>
                            <div class="field-half">
                                <input type="email" class="form-control" name="email" id="email" placeholder="@lang('label.Email_Id')" required>
                                <div class="email_error"></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="field">
                            <textarea class="form-control" name="message" id="message" placeholder="@lang('label.Message')"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="field">
                            <div class="text-center button-wrap">
                                <input type="submit" class="btn btn-primary" name="SEND" value="@lang('label.SEND')" >                                
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- ************* content-Section close********************-->

@stop
@section('javascript')    
<script>
    $('.accordion-wrapper').each(function () {
        $(this).find('.plus-minus-toggle > .toggler').click(function (e) {
            $('.content-section').slideUp().parent().removeClass('active');
//                        $(this).next('ul').slideToggle().parent().toggleClass('active');
            if ($(this).parent().next('.content-section').is(':visible')) {
                $(this).parent().next('.content-section').slideUp().parent().removeClass('active');
                console.log($(this).html() + 'if');
            } else {
                $(this).parent().next('.content-section').slideToggle().parent().toggleClass('active');
                console.log($(this).html() + 'else');
            }
        });
    });
    $("#clientFaqs").validate({
        rules: {
            name: {
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
                required: '@lang("label.This field is required")',
            },
            email: {
                required: '@lang("label.This field is required")',
                email: '@lang("label.Please enter a valid email address")',
            },
            message: {
                required: '@lang("label.This field is required")',
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
            var message = $('#message').val();
            // var _token '= <?php csrf_field(); ?>';
            var _token = CSRF_TOKEN;
            var url = "{{ URL::to($language.'/faqsEmail')}}";
            $('#loader').show();
            $.ajax({
                url: url,
                method: "POST",
                data: {name: name, email: email, message: message, _token: _token},
                success: function (data) {
                    obj = jQuery.parseJSON(data);
                    $('#loader').hide();
                    if (obj.status == 1)
                    {
                        swal('@lang("label.Email Sent")!', obj.msg, 'success');
                    } else {
                        swal("@lang('label.Error')!", obj.msg, "error");
                    }
                    $("#clientFaqs")[0].reset();
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    $('#loader').hide();

                }
            });
        }
    });
</script>
@endsection