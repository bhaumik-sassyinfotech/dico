@extends('front_template.layout')
@section('content')    

<!-- ************* content-Section ********************-->
<div class="content-wrapper">
    <div class="wrap pricing-wrapper package-page">
        <div class="container">
            <h3 class="page-title">Our Packages</h3>
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
                            <div class="duration">per month</div>
                        </div>
                        <div class="content">
                            <div class="multi-use">{{$package->name}}</div>
                            <div class="user-limit">Upto {{$package->total_user}} Users</div>
                        </div>
                        <div class="bottom">
                            <a href="#" class="btn btn-{{$package->color}}">SELECT PLAN</a>
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