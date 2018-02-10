<footer>
    <div class="container">
        <div class="footer-tbl">
            <div class="footer-cell">
                <ul class="inlined other-lnk">
                    <li><a href="terms.php">Terms & Conditions</a></li>
                    <li><a href="privacy.php">Privacy Policy</a></li>
                    <li><a href="log-reg.php">Login</a></li>
                </ul>
            </div>
            <div class="footer-cell social-cell">
                <ul class="inlined social-container">
                    <li><a href="#" class="facebook"><span class="fa fa-facebook-square"></span></a></li>
                    <li><a href="#" class="twitter"><span class="fa fa-twitter"></span></a></li>
                    <li><a href="#" class="instagram"><span class="fa fa-instagram"></span></a></li>
                </ul>
            </div>
            <div class="footer-cell">
                <span>Copyrights © 2017-18 <a href="index.php">Dico</a>. All rights reserved</span>
            </div>
        </div>
    </div>
</footer>


<script type="text/javascript" src="assets/js/bootstrap.js"></script>
<script type="text/javascript" src="assets/js/owl.carousel.js"></script>
<!--<script type="text/javascript" src="assets/js/slick.js"></script>-->
<script type="text/javascript" src="assets/js/select2.min.js"></script>
<script type="text/javascript" src="assets/js/wow.js"></script>
<script type="text/javascript" src="assets/js/page.js"></script>
<script type="text/javascript" src="assets/js/flipster.js"></script>
<script type="text/javascript" src="assets/js/ion.rangeSlider.js"></script>

<script type="text/javascript" src="assets/js/video-popup.js"></script>


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