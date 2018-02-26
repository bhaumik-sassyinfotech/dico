<footer role="contentinfo">
    <div class="clearfix">
        <ul class="list-unstyled list-inline pull-left">
            <li>DICO &copy; <?php echo date('Y'); ?></li>
        </ul>
        <button class="pull-right btn btn-inverse-alt btn-xs hidden-print" id="back-to-top"><i class="fa fa-arrow-up"></i></button>
    </div>
</footer>



<!--<script type='text/javascript' src="{{asset('public/js/pusher.min.js')}}"></script>
<script type='text/javascript' src="{{asset('public/js/echo.js')}}"></script>-->
<script type='text/javascript' src="{{asset('public/js/app.js')}}"></script>
<script type='text/javascript' src="{{asset(ASSETS_URL.'js/jquery-1.10.2.min.js')}}"></script>

<script type='text/javascript' src="{{asset(ASSETS_URL.'js/jqueryui-1.10.3.min.js')}}"></script>

<script type='text/javascript' src="{{asset(ASSETS_URL.'js/bootstrap.min.js')}}"></script>
<script type='text/javascript' src="{{asset('public/js/moment.js')}}"></script>
<script type='text/javascript' src="{{asset('public/js/bootstrap-datetimepicker.min.js')}}"></script>
<script type='text/javascript' src="{{asset(ASSETS_URL.'js/enquire.js')}}"></script>


<script type='text/javascript' src="{{asset(ASSETS_URL.'js/jquery.cookie.js')}}"></script>
<script type='text/javascript' src="{{asset(ASSETS_URL.'js/jquery.nicescroll.min.js')}}"></script>
<script type='text/javascript' src="{{asset('assets/js/jquery.slimscroll.js')}}"></script>
<script type='text/javascript' src="{{asset(ASSETS_URL.'plugins/codeprettifier/prettify.js')}}"></script>
<script type='text/javascript' src="{{asset(ASSETS_URL.'plugins/easypiechart/jquery.easypiechart.min.js')}}"></script>
<script type='text/javascript' src="{{asset(ASSETS_URL.'plugins/sparklines/jquery.sparklines.min.js')}}"></script>
<script type='text/javascript' src="{{asset(ASSETS_URL.'plugins/form-toggle/toggle.min.js')}}"></script>
<script type='text/javascript' src="{{asset(ASSETS_URL.'plugins/fullcalendar/fullcalendar.min.js')}}"></script>
<script type='text/javascript' src="{{asset(ASSETS_URL.'plugins/form-daterangepicker/daterangepicker.min.js')}}"></script>
<script type='text/javascript' src="{{asset(ASSETS_URL.'plugins/form-daterangepicker/moment.min.js')}}"></script>
<script type='text/javascript' src="{{asset(ASSETS_URL.'plugins/charts-flot/jquery.flot.min.js')}}"></script>
<script type='text/javascript' src="{{asset(ASSETS_URL.'plugins/charts-flot/jquery.flot.resize.min.js')}}"></script>
<script type='text/javascript' src="{{asset(ASSETS_URL.'plugins/charts-flot/jquery.flot.orderBars.min.js')}}"></script>
<script type='text/javascript' src="{{asset(ASSETS_URL.'plugins/pulsate/jQuery.pulsate.min.js')}}"></script>
<!--<script type='text/javascript' src="{{asset('assets/demo/demo-index.js')}}"></script>
<script type='text/javascript' src="{{asset('assets/js/placeholdr.js')}}"></script>
<script type='text/javascript' src="{{asset('assets/js/application.js')}}"></script>
<script type='text/javascript' src="{{asset('assets/demo/demo.js')}}"></script> -->
<script type='text/javascript' src="{{asset('public/assets/js/jquery.validate.min.js')}}"></script>
<link href="{{ asset('public/assets/fonts/glyphicons/css/glyphicons.css') }}" rel="stylesheet">
<?php /*
  <link href="https://datatables.yajrabox.com/css/datatables.bootstrap.css" rel="stylesheet">
  <script src="https://datatables.yajrabox.com/js/jquery.dataTables.min.js"></script>
  <script src="https://datatables.yajrabox.com/js/datatables.bootstrap.js"></script>
 */ ?>
<link href="{{ asset('public/yajratable/css/datatables.bootstrap.css') }}" rel="stylesheet">
<script src="{{ asset('public/yajratable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('public/yajratable/js/datatables.bootstrap.js') }}"></script>

<script type='text/javascript' src="{{asset('public/js/sweetalert.min.js')}}"></script>
<script src="{{ asset('public/js/select2.js') }}"></script>
<script type='text/javascript' src="{{ asset('public/js/application.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/mixitup/jquery.mixitup.min.js') }}"></script>
<!-- js to exclude profanity ( https://github.com/ChaseFlorell/jQuery.ProfanityFilter ) -->
<script src="{{ asset('public/js/jquery.profanityfilter.js') }}" type="text/javascript"></script>
<script type='text/javascript' src="{{asset('public/js/custom/common.js')}}"></script>
<script type='text/javascript' src="{{asset('public/js/custom/custom.js')}}"></script>
<script type='text/javascript' src="{{asset('public/js/custom/custom_bm.js')}}"></script>
<script src="{{asset(ASSETS_URL.'js/tag-it.js')}}" type="text/javascript" charset="utf-8"></script>
<script type='text/javascript' src='{{asset("assets/plugins/bootbox/bootbox.min.js")}}'></script>
<script type='text/javascript' src='{{asset("assets/demo/demo-modals.js")}}'></script>
<script type='text/javascript' src='{{asset("assets/demo/demo-gallery-simple.js")}}'></script>
<script type='text/javascript' src='{{asset("assets/js/placeholdr.js")}}'></script>
<script type='text/javascript' src="{{asset('assets/demo/demo.js')}}"></script>
<script type='text/javascript' src="{{asset('assets/js/previews.js')}}"></script>
<script type='text/javascript' src="{{asset('assets/js/owl.carousel.min.js')}}"></script>
<script type='text/javascript' src="{{asset('public/ckeditor/ckeditor.js')}}"></script>
<script type='text/javascript' src="{{asset('public/ckeditor/sample.js')}}"></script>
<script type="text/javascript">
                        var changeLang = function (LANG) {
                        console.log('LANG', LANG);
                        var val = LANG;
                        var path = window.location.href;
                        var langPath = path.split('/');
                        langPath[4] = LANG;
                        var url = langPath.join('/');
                        console.log('url', url);
                        if (localStorage.mylang) {
                        localStorage.mylang = LANG;
                        } else {
                        localStorage.mylang = 'en';
                        }
                        window.location.href = url;
                        };
<?php
if (Auth::user()) {
    $companyLang = DB::table('companies')->select('language')->where('id', Auth::user()->company_id)->first();
    //dd($companyLang);
    if ($companyLang != null) {
        $companyLang = $companyLang->language;
    } else {
        $companyLang = 'en';
    }
} else {
    $companyLang = 'en';
}
if (Auth::user()) {
    ?>
                            //localStorage.mylang= 'en';
                            var MYLANG = '<?= $companyLang; ?>';
                            var path = window.location.href;
                            var langPath = path.split('/');
                            langPath[4] = LANG;
                            if (langPath[4] != MYLANG){
                            changeLang(MYLANG);
                            }
                            $(document).ready(function () {
    //                            var path = window.location.href;
    //                            var langPath = path.split('/');
    //                            langPath[4] = LANG;
    //                            if (langPath[4] != MYLANG){
    //                            changeLang(MYLANG);
    //                            }
                            });
                            if ($('#editor').val()){
                            //console.log('dd',$('#editor').val());
                            window.onload = function(){
                            initSample();
                            }
                            }
<?php } ?>
</script>
<script>
</script>    
<script type='text/html' id="notificationData">
    <li>
        <a href="{URL}" class="notification-user active">
            <span class="time">{TIME}</span>
            <i class="fa fa-user"></i>
            <span class="msg">{MSG}</span>
        </a>
    </li>
</script>
<script type='text/javascript'>
                            function getNotificationHtml(data){
                            return $('#notificationData').html().replace('{URL}', data.url).replace('{TIME}', data.time).replace('{MSG}', data.msg);
                            }
</script>
<script type='text/javascript'>
   <?php
    if (Auth::user()) {
        ?>                       
Echo.channel('activity')
  .listen('.comment.added', (e) => {

                                    $('#event').append('<li>' + e.comment + '</li>');
    }    )
    .listen('.message.register', (data) => {
                                    console.log(data);
                            $('.scrollthis').append(getNotificationHtml(data))
})
/* .listen('.message.postUpdate', (data) => {
                                    console.log(data);
                            $('.scrollthis').append(getNotificationHtml(data));
})*/;
  
    Echo.channel('activity.{{ \Auth::user() - > id }}')
    .listen('.message.postUpdate', (data) => {
                                        console.log(data);
                                $('.scrollthis').append(getNotificationHtml(data));
    });
    <?php } ?>

</script>