<meta charset="utf-8">
	<title>Avant</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Avant">
	<meta name="author" content="The Red Team">

    <!-- <link href="assets/less/styles.less" rel="stylesheet/less" media="all">  -->
    <link rel="stylesheet" href="{{asset('public/assets/css/styles.css?=140')}}">
    <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600' rel='stylesheet' type='text/css'>

    <link href="{{asset('public/assets/demo/variations/default.css')}}" rel='stylesheet' type='text/css' media='all' id='styleswitcher'> 
    <link href="{{asset('public/assets/demo/variations/default.css')}}" rel='stylesheet' type='text/css' media='all' id='headerswitcher'> 
    
	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries. Placeholdr.js enables the placeholder attribute -->
	<!--[if lt IE 9]>
        <link rel="stylesheet" href="assets/css/ie8.css">
		<script type="text/javascript" src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/respond.js/1.1.0/respond.min.js"></script>
        <script type="text/javascript" src="assets/plugins/charts-flot/excanvas.min.js"></script>
	<![endif]-->

	<!-- The following CSS are included as plugins and can be removed if unused-->

<link rel='stylesheet' type='text/css' href="{{asset('public/assets/plugins/form-daterangepicker/daterangepicker-bs3.css')}}" /> 
<link rel='stylesheet' type='text/css' href="{{asset('public/assets/plugins/fullcalendar/fullcalendar.css')}}" /> 
<link rel='stylesheet' type='text/css' href="{{asset('public/assets/plugins/form-markdown/css/bootstrap-markdown.min.css')}}" /> 
<link rel='stylesheet' type='text/css' href="{{asset('public/assets/plugins/codeprettifier/prettify.css')}}" /> 
<link rel='stylesheet' type='text/css' href="{{asset('public/assets/plugins/form-toggle/toggles.css')}}" /> 
<link rel='stylesheet' type='text/css' href="{{asset('public/css/sweetalert.css')}}" />
<link href="{{asset('public/css/select2.css')}}" rel="stylesheet" />
<script type="text/javascript">
    var SITE_URL = '<?php echo url('/'); ?>';
    var ROUTE_COMPANY_STORE = "{{route('company.store')}}";
    var CSRF_TOKEN = "{{ csrf_token() }}";
	var EXTERNAL_SWEARS = "{{asset('public/profanity/swearWords.json')}}";
</script>
<style type="text/css">
	.error {
		color:#ff4a4a;
	}
</style>