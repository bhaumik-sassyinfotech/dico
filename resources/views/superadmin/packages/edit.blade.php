@extends('template.default')
<title>@lang("label.adDICO - Packages")</title>
@section('content')

@include('template.notification')

<div id="page-content">
    <div id='wrap'>
        <div id="page-heading">
            <ol class="breadcrumb">
                <li><a href="{{ url('/home') }}">@lang("label.adDashboard")</a></li>
                <li><a href="{{ route('packages.index') }}">@lang("label.adPackages")</a></li>
                <li class="active">@lang("label.adUpdate Packages")</li>
            </ol>
            <h1 class="tp-bp-0">@lang("label.adUpdate Packages")</h1>
            <hr class="border-out-hr">
            <?php /* <div>
              <div class="col-md-6 pull-right nopadding"><p style="float:right;"><a href="{{ url('/home') }}">Dashboard</a> > <a href="{{ route('security_question.index') }}">Security Question</a> > Update Security Question</p></div>
              </div> */ ?>
        </div>
        <div class="container">
            <div class="row">
                <div id="create-user-from" style="width: 100%">
                    <form name="packageEdit" id="packageEdit" method="POST"
                          action="{{ route('packages.update',[$packages->id]) }}" class="common-form" >
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}
                        <div class="form-group">
                            <label class="text-15">Name<span>*</span></label>
                            <input type="text" name="name" id="name"
                                   placeholder="Name"
                                   class="form-control required" value="{{$packages->name}}">
                        </div>
                        <div class="form-group">
                            <label class="text-15">Amount<span>*</span></label>
                            <input type="text" name="amount" id="amount"
                                   placeholder="Amount"
                                   class="form-control required" value="{{$packages->amount}}">
                        </div>
                         <div class="form-group">
                            <label class="text-15">Total User<span>*</span></label>
                            <input type="text" name="total_user" id="total_user"
                                   placeholder="Total User"
                                   class="form-control required" value="{{$packages->total_user}}">
                        </div>
                        <div class="form-group">
                            <div class="btn-wrap-div">
                                <input type="submit" class="st-btn" value="@lang('label.adSubmit')">
                                <a href="{{ url()->previous() }}" class="st-btn">@lang("label.adBack")</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('javascript')

@endsection