<meta charset="utf-8">
	<title>Avant</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Avant">
	<meta name="author" content="The Red Team">

    <!-- <link href="assets/less/styles.less" rel="stylesheet/less" media="all">  -->
    <link rel="stylesheet" href="{{asset(ASSETS_URL.'css/styles.css?=140')}}">
    <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600' rel='stylesheet' type='text/css'>

    <link href="{{asset('public/assets/demo/variations/default.css')}}" rel='stylesheet' type='text/css' media='all' id='styleswitcher'> 
    <link href="{{asset('public/assets/demo/variations/default.css')}}" rel='stylesheet' type='text/css' media='all' id='headerswitcher'> 
   

<link rel='stylesheet' type='text/css' href="{{asset('assets/css/styles.css')}}" />
<link rel='stylesheet' type='text/css' href="{{asset('assets/css/main-responsive.css')}}" /> 
<link rel='stylesheet' type='text/css' href="{{asset('assets/css/main-style.css')}}" /> 

<link rel='stylesheet' type='text/css' href="{{asset(ASSETS_URL.'plugins/codeprettifier/prettify.css')}}" /> 
<link rel='stylesheet' type='text/css' href="{{asset(ASSETS_URL.'plugins/form-toggle/toggles.css')}}" />
<link rel='stylesheet' type='text/css' href="{{asset(ASSETS_URL.'plugins/form-daterangepicker/daterangepicker-bs3.css')}}" /> 
<link rel='stylesheet' type='text/css' href="{{asset(ASSETS_URL.'plugins/fullcalendar/fullcalendar.css')}}" /> 
<link rel='stylesheet' type='text/css' href="{{asset(ASSETS_URL.'plugins/form-markdown/css/bootstrap-markdown.min.css')}}" /> 
<link rel='stylesheet' type='text/css' href="{{asset('public/css/sweetalert.css')}}" />
<link rel='stylesheet' type='text/css' href="{{asset('public/css/bootstrap-datetimepicker.min.css')}}" />
<link href="{{asset('public/css/select2.css')}}" rel="stylesheet" />
<link href="{{asset(ASSETS_URL.'css/jquery.tagit.css')}}" rel="stylesheet" type="text/css">
<link href="{{asset(ASSETS_URL.'css/tagit.ui-zendesk.css')}}" rel="stylesheet" type="text/css">
<link href="{{ asset('assets/css/owl.carousel.css') }}" rel="stylesheet" type="text/css">
<script type="text/javascript">
    var SITE_URL = '<?php echo url('/'); ?>';
    var ROUTE_COMPANY_STORE = "{{route('company.store')}}";
    var CSRF_TOKEN = "{{ csrf_token() }}";
    var EXTERNAL_SWEARS = "{{ asset('public/profanity/swearWords.json') }}";
    var POST_TITLE_LIMIT = "{{POST_TITLE_LIMIT}}";
    var DEFAULT_PROFILE_IMAGE = "{{DEFAULT_PROFILE_IMAGE}}";
    var PROFILE_PATH = "{{PROFILE_PATH}}";
    var ASSETS_PATH = "{{asset('')}}";
</script>
<style type="text/css">
	.error {
		color:#ff4a4a !important;
	}
</style>