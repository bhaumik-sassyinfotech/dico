<?php
    $language = App::getLocale();
?>
<header class="">
    <div class="container">
        <div class="header-tbl">
            <div class="header-tbl-cell logo">
                <div class="logo-header">
                    <a href="{{url($language.'/front')}}"><img src="{{asset('public/front/images/logo.png')}}" alt="DICO"></a>
                </div>
            </div>
            <div class="header-tbl-cell nav nav-links">
                <div class="mobile-menu-button-section">
                    <button class="c-hamburger c-hamburger--htx mob-menu-click">
                        <span>toggle menu</span>
                    </button>
                </div>
                <div class="select-lang-sec"> <label for="sel1">Language :</label> 
                    <select class="form-control" id="sel1" onchange="changeLang(this)"> 
                        <option value="en">English</option> 
                        <option value="sp"  selected="selected">Spanish</option> 
                    </select>
                </div>
                <div class="menu-section">
                    <ul class="inlined">
                        <li class="{{ Request::is($language.'front') ? 'current-page' : '' }}"><a href="{{url($language.'/front')}}">@lang("label.home")</a></li>
                        <li class="{{ Request::is($language.'how-it-works') ? 'current-page' : '' }}"><a href="{{url($language.'/how-it-works')}}">@lang("label.How It Works")</a></li>
                        <li class="{{ Request::is($language.'why-us') ? 'current-page' : '' }}"><a href="{{url($language.'/why-us')}}">@lang("label.Why Us") </a></li>
                        <li class="{{ Request::is($language.'package') ? 'current-page' : '' }}"><a href="{{url($language.'/package')}}">@lang("label.Packages")</a></li>
                        <li class="{{ Request::is($language.'faqs') ? 'current-page' : '' }}"><a href="{{url($language.'/faqs')}}">@lang("label.Faqs")</a></li>
                        <li class="{{ Request::is($language.'contactUs') ? 'current-page' : '' }}"><a href="{{url($language.'/contactUs')}}">@lang("label.Contact Us")</a></li>

                        <li style="display: inline-block;" class="{{ Request::is( 'users-login') ? 'current-page' : '' }}">
                            <?php
                            $auth = Auth::guard('front')->user();
                            if (!$auth) {
                                ?>
                                <a href="{{url($language.'/users-login')}}">@lang("label.LoginRegister")</a>
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
                                if (Auth::guard('front')->user()->profile_image != '') {
                                    ?>
                                    <img src="<?= PROFILE_PATH . Auth::guard('front')->user()->profile_image; ?>">                               
                                <?php } else {
                                    ?>  
                                    <img src="{{asset('public/front/images/user.png')}}">
                                <?php } ?>
                            </div><span class="use-name">User-{{Auth::guard('front')->user()->name}}</span></a>
                        <ul class="user-list-menu">
                            <li><a href="{{url($language.'/front-logout')}}">@lang("label.Logout")</a> </li>
                        </ul>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</header>