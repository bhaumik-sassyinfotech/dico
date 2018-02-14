@extends('front_template.layout')
@section('content')    
<!-- ************* content-Section ********************-->
<div class="content-wrapper">

    <!-- Slider section-->
    <div class="wrap slider-wrapper">
        <div class="container">
            <div class="slider-relatived">
                <ul class="slider-ul skewed-clock">
                    <li class="item skewed-anticlock" style="background-image: url('{{asset('public/front/images/slider-top-banner.png')}}')">
                        <div class="grad-overlay"></div>
                    </li>
                </ul>
                <div class="over-text wow fadeIn" data-wow-duration="2s">
                    <h1 class="big-title">{{Helpers::getBlockTitle('empower_your_employees_to_be_happy')}}</h1>
                    <p class="desc">{{strip_tags(Helpers::getBlockDescription('empower_your_employees_to_be_happy'))}}</p>
                    <div class="button-wrap">
                        <a href="#" class="btn btn-icon-primary">Start Trial<img src="{{asset('public/front/images/btn-wht-arrow.png')}}" class="anim-arrow"></a>
                    </div>
                </div>
                <div class="float-image wow fadeIn" data-wow-delay="0.5s" data-wow-duration="2s">
                    <img src="{{asset('public/front/images/slider-pc.png')}}">
                </div>
            </div>
        </div>
    </div>
    <!-- Slider section close-->

    <!-- App section-->
    <div class="wrap app-feat-wrap">
        <div class="container">
            <h3 class="main-title"><?= html_entity_decode(Helpers::getBlockTitle('app_features'));?></h3>
            <div class="sub-title">{{strip_tags(Helpers::getBlockDescription('app_features'))}}</div>
            <div class="container-md">
                <ul class="grid">
                    <li class="grid-item wow fadeIn">
                        <div class="box">
                            <div class="top">
                                <div class="img">
                                    <img src="{{asset('public/front/images/app-feature-1.png')}}" alt="">
                                </div>
                                <div class="text">
                                    <a href="#" class="ttl">{{Helpers::getBlockTitle('ideas')}}</a>
                                </div>
                            </div>
                            <div class="bottom">
                                {{strip_tags(Helpers::getBlockDescription('ideas'))}}
                            </div>
                        </div>
                    </li>

                    <li class="grid-item wow fadeIn" data-wow-delay="0.5s">
                        <div class="box">
                            <div class="top">
                                <div class="img">
                                    <img src="{{asset('public/front/images/app-feature-2.png')}}" alt="">
                                </div>
                                <div class="text">
                                    <a href="#" class="ttl">{{Helpers::getBlockTitle('inquiries')}}</a>
                                </div>
                            </div>
                            <div class="bottom">
                               {{strip_tags(Helpers::getBlockDescription('inquiries'))}}
                            </div>
                        </div>
                    </li>

                    <li class="grid-item wow fadeIn" data-wow-delay="1s">
                        <div class="box">
                            <div class="top">
                                <div class="img">
                                    <img src="{{asset('public/front/images/app-feature-3.png')}}" alt="">
                                </div>
                                <div class="text">
                                    <a href="#" class="ttl">{{Helpers::getBlockTitle('CHALLENGES')}}</a>
                                </div>
                            </div>
                            <div class="bottom">
                             {{strip_tags(Helpers::getBlockDescription('CHALLENGES'))}}
                            </div>
                        </div>
                    </li>

                    <li class="grid-item wow fadeIn" data-wow-delay="1.5s">
                        <div class="box">
                            <div class="top">
                                <div class="img">
                                    <img src="{{asset('public/front/images/app-feature-4.png')}}" alt="">
                                </div>
                                <div class="text">
                                    <a href="#" class="ttl">{{Helpers::getBlockTitle('MEETINGS')}}</a>
                                </div>
                            </div>
                            <div class="bottom">
                               {{strip_tags(Helpers::getBlockDescription('MEETINGS'))}}
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- App section close-->


    <!--video-presentation-->
    <div class="wrap video-pr-wrap">
        <div class="container">
            <div class="video-wrap-tbl">
                <div class="video-wrap-cell img wow fadeIn" data-wow-duration="2s">
                    <div class="lap-img">
                        <img src="{{asset('public/front/images/video-wrap-pc.png')}}" alt="">
                    </div> 
                </div>
                <div class="video-wrap-cell content wow fadeIn" data-wow-delay="0.5s" data-wow-duration="2s">
                    <h3 class="main-title"><?= html_entity_decode(Helpers::getBlockTitle('VIDEOPRESENTATION'));?></h3>
                    <div class="sub-title"><?= html_entity_decode(Helpers::getBlockDescription('VIDEOPRESENTATION'));?></div>
                    <div class="button-wrap">
                        <a href="https://www.youtube.com/watch?v=tO01J-M3g0U" class="btn btn-icon-video play-video">VIEW VIDEO<img src="{{asset('public/front/images/video-play.png')}}" class="anim-arrow"></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--video-presentation close-->

    <!--alternate section-->
    <div class="wrap alternet-section-wrap">
        <div class="container">
            <div class="alter-wrapper">
                <div class="alter-wrapper-item wow fadeIn" data-wow-duration="2s">
                    <div class="alter-wrapper-cell desc">
                        <div class="title"><?= html_entity_decode(Helpers::getBlockTitle('promote_team_work'));?></div>
                        <?= html_entity_decode(Helpers::getBlockDescription('promote_team_work'));?>
                    </div>
                    <div class="alter-wrapper-cell">
                        <div class="img-wrap">
                            <img src="{{asset('public/front/images/alter-1.png')}}" alt="">
                        </div>
                    </div>
                </div>
                <div class="alter-wrapper-item wow fadeIn" data-wow-duration="2s">
                    <div class="alter-wrapper-cell">
                        <div class="img-wrap">
                            <img src="{{asset('public/front/images/alter-2.png')}}" alt="">
                        </div>
                    </div>
                    <div class="alter-wrapper-cell desc">
                        <div class="title">{{Helpers::getBlockTitle('share_your_ideas_questions_or_challenge')}}</div>
                         <?= html_entity_decode(Helpers::getBlockDescription('share_your_ideas_questions_or_challenge'))?>
                    </div>
                </div>
                <div class="alter-wrapper-item wow fadeIn" data-wow-duration="2s">
                    <div class="alter-wrapper-cell desc">
                        <div class="title"><?= html_entity_decode(Helpers::getBlockTitle('repository_of_information'));?></div>
                        <?= html_entity_decode(Helpers::getBlockDescription('repository_of_information'));?>
                    </div>
                    <div class="alter-wrapper-cell">
                        <div class="img-wrap">
                            <img src="{{asset('public/front/images/alter-3.png')}}" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--alternate section close-->
    <!--portal slider-->
    <div class="wrap portal-section-wrap">
        <div class="container">
            <div class="portal-wrapper">
                <h3 class="main-title"><?= html_entity_decode(Helpers::getBlockTitle('web_portal'));?></h3>
                <div class="sub-title"><?= html_entity_decode(Helpers::getBlockDescription('web_portal'));?></div>
                <div class="portal-slider-wrap">
                    <ul class="portal-slider">
                        <li><img src="{{asset('public/front/images/portal-1.png')}}"></li>
                        <li><img src="{{asset('public/front/images/portal-1.png')}}"></li>
                        <li><img src="{{asset('public/front/images/portal-1.png')}}"></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!--portal slider close-->
    <!--pricing section -->
    <div class="wrap pricing-wrapper">
        <div class="container">
            <h3 class="main-title"><?= html_entity_decode(Helpers::getBlockTitle('OUR_PRICING'));?></h3>
            <div class="sub-title"><?= html_entity_decode(Helpers::getBlockDescription('OUR_PRICING'));?></div>
            <div class="pricing-wrap-grid">
                <div class="pricing-grid">
                     @foreach ($packageList as $package)
                    <div class="pricing-item {{$package->color}}">
                        <div class="top">
                            <div class="price">${{$package->amount}}</div>
                            <div class="duration">per month</div>
                        </div>
                        <div class="content">
                            <div class="multi-use">{{$package->name}}</div>
                            <div class="user-limit">Upto {{$package->total_user}} Users</div>
                        </div>
                        <div class="bottom">
                              <a href="{{url('registerPackage/')}}{{'/'}}{{$package->slug_name}}" class="btn btn-{{$package->color}}">SELECT PLAN</a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <!-- pricing section close-->
</div>
<!-- ************* content-Section close********************-->
@stop
@section('javascript')    
<script>
    $(".portal-slider-wrap").flipster({
        style: 'flat',
        itemContainer: '.portal-slider',
        scrollwheel: false,
        buttons: true,
        spacing: -0.85
    });
    jQuery(function () {
        jQuery(".play-video").YouTubePopUp();
    });

</script>
@endsection