@extends('template.default')
<title>@lang("label.adDICO - Company")</title>
@section('content')
<style>
    .pricing-item {
        float: left;
        width: 20%;
    }
    .pricing-grid::after {
        content: "";
        display: block;
        clear: both;
    }
    .pricing-grid {
        position: relative;
        display: block;
    }

</style>

<div id="page-content" class="create-user create-user-popup package-page" style="min-height: 650px;">
    <div id='wrap'>
        <div id="page-heading">
            <ol class="breadcrumb">
                <li><a href="{{ route('index') }}">@lang("label.adDashboard")</a></li>
                <li><a href="{{ url('/updateCompany') }}">@lang("label.adCompany")</a></li>
                <li class="active">@lang("label.adUpdate Company")</li>
            </ol>
            <h1 class="tp-bp-0">@lang("label.adCompany")</h1>
            <hr class="border-out-hr">
        </div>

        <div class="container">
            <div class="row">
                <!-- <form name="company_form" id="company_form" method="post" action="{{route('company.update',$company->company_id)}}">-->
                <div id="company-from">
                    {!! Form::model($company, ['method' => 'POST', 'url' => ['updateCompany'],'enctype'=>'multipart/form-data', 'id' => 'company_form', 'class' => 'common-form']) !!}
                    {{ csrf_field() }}
                    <input type="hidden" value="{{$company->package_id}}" name="package_id" id="package_id">
                    <div class="package-left">
                        <div class="form-group">
                            <label class="text-15">@lang("label.adLanguage")<span>*</span></label>
                            <div class="select">
                                <select name="language" id="language" class="form-control required">
                                    <option selected="" disabled="" value="">@lang("label.adSelect Language")</option>
                                    <option value="en" <?php
                                    if ($company->language == 'en') {
                                        echo "selected";
                                    }
                                    ?>>English</option>
                                    <option value="sp" <?php
                                    if ($company->language == 'sp') {
                                        echo "selected";
                                    }
                                    ?>>Spanish</option>
                                </select>
                            </div>
                        </div>

                        <div class="        form-group">
                            <label class="text-15">@lang("label.adCompany Name")<span>*</span></label>
                            <input type="text" name="company_name" id="company_name" value="{{$company->company_name}}" placeholder="@lang('label.adCompany Name')" class="form-control required" readonly="">
                        </div>
                        <div class="form-group">
                            <label class="text-15">@lang('label.adCompany Description')</label>
                            <textarea name="company_description" id="company_description" placeholder="@lang('label.adCompany Description')" class="form-control">{{$company->description}}</textarea>
                        </div>
                    </div>
                    <div class="package-right">
                        <div class="form-group">
                            <p>@lang('label.adCompany Logo')<span>*</span></p>
                            <div class="upload-btn-wrapper">
                                <button class="upload-btn">@lang('label.adUpload Files')</button>
                                <input type="file" name="file_upload" id="file_upload" class="file-upload__input" value="{{asset(UPLOAD_PATH.$company->company_logo)}}">
                            </div>
                        </div>
                        <div class="preview-box">
                            <?php
                            if (empty($company->company_logo)) {
                                $company_logo = DEFAULT_COMPANY_LOGO;
                            } else {
                                $company_logo = UPLOAD_PATH . $company->company_logo;
                            }
                            ?>
                            <input type="hidden" id="company_logo" name="company_logo" value="{{$company->company_logo}}">
                            <img src="{{asset($company_logo)}}" id="user-profile" height="135" width="135">
                        </div>
                    </div>    
                    <div class="form-group submit-package">
                        <div class="btn-wrap-div">
                            <input type="submit" name="save" id="save" class="st-btn" value="@lang('label.adSubmit')" /> 

                        </div>
                    </div>
                    {!! Form::close() !!}    
                    <!--package code -->

                </div>
                <div class="wrap pricing-wrapper">
                    <div class="container">
                        <h3 class="main-title"><?= html_entity_decode(Helpers::getBlockTitle('OUR_PRICING')); ?></h3>
                        <div class="sub-title">Upgrad Your Package</div>
                        <div class="pricing-wrap-grid">
                            <div class="pricing-grid">
                                @foreach ($packageList as $package)
                                <?php
                                if ($company->package_id == $package->id) {
                                    $mypackage_id = $package->id;
                                }
                                ?>


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
                                        <a href="javascript:void()" class="btn btn-{{$package->color}} ">
                                            <?php
                                            //echo $company->package_id.'==='.$package->id;
                                            if ($company->package_id == $package->id) {
                                                echo __('label.adCURRENT PLAN');
                                            } else if ($company->package_id > $package->id) {
                                                echo __('label.adDOWNGREAD PLAN');
                                            } else if ($company->package_id < $package->id) {
                                                echo __('label.adUPGRAD PLAN');
                                            }
                                            ?>
                                        </a>
                                        <?php
                                        if ($package->id != $company->package_id) {
                                            //   echo $package->id; die;    
                                            ?>

                                            <form method="POST" action="{{url('packageUpgrade')}}">
                                                {{ csrf_field() }}
                                                <input type="hidden" value="{{$company->package_id}}" name="package_id" id="package_id">
                                                <input type="hidden" value="{{$package->id}}" name="new_package_id" id="new_package_id">
                                                <input type="hidden" value="{{$package->amount}}" name="amount" id="new_package_id">
                                                <script
                                                    src="https://checkout.stripe.com/checkout.js"
                                                    class="stripe-button"
                                                    data-key="pk_test_Frwo3hFAcKPEU2GYHQhSPhaz"
                                                    data-image="{{asset($company_logo)}}"
                                                    data-name="DICO"
                                                    data-description="{{$package->name}}"
                                                    data-amount="{{$package->amount * 100}}">
                                                </script>
                                            </form>
                                        <?php } ?>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <!--end package code-->
            </div>
        </div>
    </div>
</div>
</div>
@stop
@section('javascript')
<script type="text/javascript">
</script>
@endsection