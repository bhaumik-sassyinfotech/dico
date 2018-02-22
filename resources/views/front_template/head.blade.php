<meta charset="UTF-8">
<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
<meta id="lang" name="langauge" value="{{ App::getLocale()}}">

<link rel="shortcut icon" href="{{asset('public/front/images/favicon.ico')}}" type="image/ico" sizes="16x16">

<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
<link rel="stylesheet" href="{{asset('public/front/css/bootstrap.min.css')}}">
<link rel="stylesheet" href="{{asset('public/front/css/main.css')}}">
<link rel="stylesheet" href="{{asset('public/front/css/responsive.css')}}">
<link rel="stylesheet" href="{{asset('public/front/css/normalize.css')}}">
<link rel="stylesheet" href="{{asset('public/front/css/owl.carousel.css')}}">
<link rel="stylesheet" href="{{asset('public/front/css/owl.theme.default.css')}}">

<link rel="stylesheet" href="{{asset('public/front/css/font-awesome.min.css')}}" type="text/css" />
<link rel="stylesheet" href="{{asset('public/front/css/select2.min.css')}}" type="text/css" />
<link rel="stylesheet" href="{{asset('public/front/css/animate.css')}}" type="text/css" />
<link rel="stylesheet" href="{{asset('public/front/css/flipster.css')}}" type="text/css" />

<link rel="stylesheet" href="{{asset('public/front/css/ion.rangeSlider.css')}}" />
<link rel="stylesheet" href="{{asset('public/front/css/ion.rangeSlider.skinNice.css')}}" />

<link rel="stylesheet" href="{{asset('public/front/css/video-popup.css')}}" />
<link rel="stylesheet" href="{{asset('public/front/css/devmain.css')}}" />

<link rel='stylesheet' type='text/css' href="{{asset('public/css/sweetalert.css')}}" />
<script type="text/javascript" src="{{asset('public/front/js/jquery.min.js')}}"></script>
<script type="text/javascript" src="{{asset('public/front/js/nice-scroll.js')}}"></script>


<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
<script>
    var CSRF_TOKEN = "{{ csrf_token() }}";
    var LANG = "{{ App::getLocale() }}";           
</script>

<div class="loader-group" id="loader" style="/* display: none; */">
    <div class="loader">
        <img src="{{asset('public/front/images/spinner.png')}}">
    </div>
</div>
