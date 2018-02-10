<footer>
    <div class="container">
        <div class="footer-tbl">
            <div class="footer-cell">
                <ul class="inlined other-lnk">
                    <li><a href="{{url('/teams-condition')}}">Terms & Conditions</a></li>
                    <li><a href="{{url('/privacy-policy')}}">Privacy Policy</a></li>
                    <li><a href="{{url('/users-login')}}">Login</a></li>
                </ul>
            </div>
            <div class="footer-cell social-cell">
                <ul class="inlined social-container">
                    <li><a href="{{Helpers::getSettings()->facebook}}" class="facebook" target="_blank"><span class="fa fa-facebook-square"></span></a></li>
                    <li><a href="{{Helpers::getSettings()->twitter}}" class="twitter" target="_blank"><span class="fa fa-twitter"></span></a></li>
                    <li><a href="{{Helpers::getSettings()->instagram}}" class="instagram" target="_blank"><span class="fa fa-instagram"></span></a></li>
                </ul>
            </div>
            <div class="footer-cell">
                <span>Copyrights © <?=date('Y');?> <a href="{{url('/front')}}">Dico</a>. {{Helpers::getSettings()->copyright}}</span>
            </div>
        </div>
    </div>
</footer>
