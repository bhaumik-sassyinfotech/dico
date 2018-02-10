<header class="">
    <div class="container">
        <div class="header-tbl">
            <div class="header-tbl-cell logo">
                <div class="logo-header">
                    <a href="index.php"><img src="{{asset('public/front/images/logo.png')}}" alt="DICO"></a>
                </div>
            </div>
            <div class="header-tbl-cell nav nav-links">
                <div class="mobile-menu-button-section">
                    <button class="c-hamburger c-hamburger--htx mob-menu-click">
                        <span>toggle menu</span>
                    </button>
                </div>
                <div class="menu-section">
                    <ul class="inlined">
                        <li class="{{ Request::is( 'front') ? 'current-page' : '' }}"><a href="{{url('/front')}}">Home</a></li>
                        <li class="{{ Request::is( 'how-it-works') ? 'current-page' : '' }}"><a href="{{url('/how-it-works')}}">How It Works</a></li>
                        <li class="{{ Request::is( 'why-us') ? 'current-page' : '' }}"><a href="{{url('/why-us')}}">Why Us </a></li>
                        <li class="{{ Request::is( 'package') ? 'current-page' : '' }}"><a href="{{url('/package')}}">Packages</a></li>
                        <li class="{{ Request::is( 'faqs') ? 'current-page' : '' }}"><a href="{{url('/faqs')}}">Faqs</a></li>
                        <li class="{{ Request::is( 'contactUs') ? 'current-page' : '' }}"><a href="{{url('/contactUs')}}">Contact Us</a></li>
                       
                            <li style="display: inline-block;" class="{{ Request::is( 'users-login') ? 'current-page' : '' }}">
                                 <?php
                        $auth = Auth::guard('front')->user();
                        if (!$auth) {
                            ?>
                                <a href="{{url('/users-login')}}">Login/Register</a>
                            <?php } ?>
                            </li>
                        
                    </ul>
            </div>
                <?php 
                if ($auth) {                    
                ?>
                <div class="user-pro" style="display: inline-block;">
                    <a href="javascript:void(0);" class="user-list"><div class="sml-img">
                            <?php 
                                if(Auth::guard('front')->user()->profile_image !=''){
                                    ?>
                                <img src="<?= PROFILE_PATH.Auth::guard('front')->user()->profile_image; ?>">                               
                                    <?php }else{
                            ?>  
                            <img src="{{asset('public/front/images/user.png')}}">
                                <?php } ?>
                        </div><span class="use-name">User-{{Auth::guard('front')->user()->name}}</span></a>
                    <ul class="user-list-menu">
                        <li><a href="{{url('/front-logout')}}">Logout</a> </li>
                    </ul>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</header>