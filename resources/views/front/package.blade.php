@extends('front_template.layout')
@section('content')    
<?php 
    $language = App::getLocale();
?>
<!-- ************* content-Section ********************-->
<div class="content-wrapper">
    <div class="wrap pricing-wrapper package-page">
        <div class="container">
            <h3 class="page-title">@lang("label.Our Packages")</h3>
            <div class="top-block-img">
                <div class="img">
                    <img src="{{asset('public/front/images/package-top.png')}}" alt="">
                </div>
            </div>
            <div class="pricing-wrap-grid">
                <div class="pricing-grid">
                    @foreach ($packageList as $package)
                    <div class="pricing-item {{$package->color}}">
                        <div class="top">
                            <div class="price">${{$package->amount}}</div>
                            <div class="duration">@lang("label.per month")</div>
                        </div>
                        <div class="content">
                            <div class="multi-use"><?php 
                                  if (App::isLocale('sp'))
                                   echo $package->spname;
                                 else 
                                    echo $package->name;   
                            ?></div>
                            <div class="user-limit">@lang("label.upto") {{$package->total_user}} @lang("label.Users")</div>
                        </div>
                        <div class="bottom">
                            <a href="{{url($language.'/registerPackage/')}}{{'/'}}{{$package->slug_name}}" class="btn btn-{{$package->color}}">@lang("label.SELECT PLAN")</a>
                        </div>
                    </div>
                    @endforeach                   
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ************* content-Section close********************-->

@stop
@section('javascript')    
@endsection