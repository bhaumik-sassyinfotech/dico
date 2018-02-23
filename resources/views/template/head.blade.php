<meta charset="utf-8">
	<title>Avant</title>
        <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="description" content="Avant">
        <meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="author" content="The Red Team">
<link rel="shortcut icon" href="{{asset('assets/img/fevicon.png')}}">
<link href="{{asset('public/css/select2.css')}}" rel="stylesheet" />
    <!-- <link href="assets/less/styles.less" rel="stylesheet/less" media="all">  -->
    <link rel="stylesheet" href="{{asset(ASSETS_URL.'css/styles.css?=140')}}">
    <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600' rel='stylesheet' type='text/css'>

    <link href="{{asset('public/assets/demo/variations/default.css')}}" rel='stylesheet' type='text/css' media='all' id='styleswitcher'> 
    <link href="{{asset('public/assets/demo/variations/default.css')}}" rel='stylesheet' type='text/css' media='all' id='headerswitcher'> 

<link rel='stylesheet' type='text/css' href="{{asset('assets/css/styles.css')}}" />
<link rel='stylesheet' type='text/css' href="{{asset('assets/css/main-style.css')}}" /> 
<link rel='stylesheet' type='text/css' href="{{asset('assets/css/main-responsive.css')}}" />

<link rel='stylesheet' type='text/css' href="{{asset(ASSETS_URL.'plugins/codeprettifier/prettify.css')}}" /> 
<link rel='stylesheet' type='text/css' href="{{asset(ASSETS_URL.'plugins/form-toggle/toggles.css')}}" />
<link rel='stylesheet' type='text/css' href="{{asset(ASSETS_URL.'plugins/form-daterangepicker/daterangepicker-bs3.css')}}" /> 
<link rel='stylesheet' type='text/css' href="{{asset(ASSETS_URL.'plugins/fullcalendar/fullcalendar.css')}}" /> 
<link rel='stylesheet' type='text/css' href="{{asset(ASSETS_URL.'plugins/form-markdown/css/bootstrap-markdown.min.css')}}" /> 
<link rel='stylesheet' type='text/css' href="{{asset('public/css/sweetalert.css')}}" />
<link rel='stylesheet' type='text/css' href="{{asset('public/css/bootstrap-datetimepicker.min.css')}}" />
<link href="{{asset(ASSETS_URL.'css/jquery.tagit.css')}}" rel="stylesheet" type="text/css">
<link href="{{asset(ASSETS_URL.'css/tagit.ui-zendesk.css')}}" rel="stylesheet" type="text/css">
<link href="{{ asset('assets/css/owl.carousel.css') }}" rel="stylesheet" type="text/css">
<!--<script type='text/javascript' src="{{asset('public/js/app.js')}}"></script>-->
<!--<script type='text/javascript' src="{{asset('public/js/pusher.min.js')}}"></script>-->
<!--<script type='text/javascript' src="{{asset('public/js/echo.js')}}"></script>-->
<script type="text/javascript">
    var SITE_URL = '<?php echo url('/'); ?>';
    var LANG = "{{ App::getLocale()}}";
    var ROUTE_COMPANY_STORE = "{{route('company.store')}}";
    var CSRF_TOKEN = "{{ csrf_token() }}";
    var EXTERNAL_SWEARS = "{{ asset('public/profanity/swearWords.json') }}";
    var POST_TITLE_LIMIT = "{{POST_TITLE_LIMIT}}";
    var DEFAULT_PROFILE_IMAGE = "{{DEFAULT_PROFILE_IMAGE}}";
    var PROFILE_PATH = "{{PROFILE_PATH}}";
    var ASSETS_PATH = "{{asset('')}}";
    var THIS_FIELD_REQUIRED = "{{__('label.This field is required')}}";
    var PLEASE_NUMERIC_VALUE = "{{__('label.Please enter numeric value only')}}";
    var PLEASE_ENTER_PROPER_VALUE = "{{__('label.Please enter proper value')}}";    
    var PLEASE_ENTER_VALID_EMAIL = "{{__('label.Please enter a valid email address')}}";    
    var PLEASE_ENTER_PROPER_URL = "{{__('label.Please enter proper url')}}";    
    var PASSWORD_NOT_MATCH = "{{__('label.Password not match')}}";    
</script>
<style type="text/css">
	.error {
		color:#ff4a4a !important;
	}
</style>