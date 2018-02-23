

<script type="text/javascript" src="{{asset('public/front/js/bootstrap.js')}}"></script>
<script type="text/javascript" src="{{asset('public/front/js/owl.carousel.js')}}"></script>
<script type="text/javascript" src="{{asset('public/front/js/select2.min.js')}}"></script>
<script type="text/javascript" src="{{asset('public/front/js/wow.js')}}"></script>
<script type="text/javascript" src="{{asset('public/front/js/page.js')}}"></script>
<script type="text/javascript" src="{{asset('public/front/js/flipster.js')}}"></script>
<script type="text/javascript" src="{{asset('public/front/js/ion.rangeSlider.js')}}"></script>
<script type="text/javascript" src="{{asset('public/front/js/video-popup.js')}}"></script>
<script type='text/javascript' src="{{asset('public/assets/js/jquery.validate.min.js')}}"></script> 
<script type='text/javascript' src="{{asset('public/js/additional-methods.min.js')}}"></script> 
<script type='text/javascript' src="{{asset('public/js/sweetalert.min.js')}}"></script>
<script>
    var wow = new WOW(
        {
            boxClass:     'wow',      // animated element css class (default is wow)
            animateClass: 'animated', // animation css class (default is animated)
            offset:       0,          // distance to the element when triggering the animation (default is 0)
            mobile:       true,       // trigger animations on mobile devices (default is true)
            live:         true,       // act on asynchronously loaded content (default is true)
            callback:     function(box) {
                // the callback is fired every time an animation is started
                // the argument that is passed in is the DOM node being animated
            },
            scrollContainer: null // optional scroll container selector, otherwise use window
        }
    );
    wow.init();

    function preloader(){
        document.getElementById("loader").style.display = "none";
        document.getElementById("content-body").style.display = "block";
    }//preloader
    window.onload = preloader;

</script>
<script>
    window._token = '{{ csrf_token() }}';
</script>
<script>
   var changeLang = function (that) {
        var val = $(that).val();
        var path = window.location.href;
        newPath = path.split('/' + LANG + '/');
        if (newPath.length > 1) {
            window.location.href = newPath[0] + '/' + val + '/' + newPath[1];
        } else {
            newPath = path.split('/' + LANG);
            window.location.href = newPath[0] + '/' + val;
        }
    };
    $(document).ready(function () {
        $('#sel1 option[value=' + LANG + ']').attr('selected', 'selected');
    });
     Echo.channel('activity')
    .listen('.comment.added', (e) => {
        $('.scrollthis').append('<li>' + e.comment + '</li>');
    });
</script>
@yield('javascript')
