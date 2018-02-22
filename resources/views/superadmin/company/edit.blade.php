@extends('template.default')
<title>@lang("label.adDICO - Company")</title>
@section('content')


<div id="page-content" class="create-user create-user-popup" style="min-height: 650px;">
    <div id='wrap'>
        <div id="page-heading">
            <ol class="breadcrumb">
                <li><a href="{{ url('/home') }}">@lang("label.adDashboard")</a></li>
                <li><a href="{{ route('company.index') }}">@lang("label.adCompany")</a></li>
                <li class="active">@lang("label.adUpdate Company")</li>
            </ol>
            <h1 class="tp-bp-0">@lang("label.adCompany")</h1>
            <hr class="border-out-hr">
        </div>

        <div class="container">
            <div class="row">
                <!-- <form name="company_form" id="company_form" method="post" action="{{route('company.update',$company->company_id)}}">-->
                <div id="company-from">
                    {!! Form::model($company, ['method' => 'PUT', 'route' => ['company.update', $company->id],'enctype'=>'multipart/form-data', 'id' => 'company_form', 'class' => 'common-form']) !!}
                    <div class="form-group">
                        <label class="text-15">@lang("label.adLanguage")<span>*</span></label>
                        <div class="select">
                            <select name="language" id="language" class="form-control required">
                                <option selected="" disabled="" value="">@lang('label.adSelect Language')</option>
                                <option value="en" <?php if($company->language == 'en') {echo "selected";}?>>English</option>
                                <option value="sp" <?php if($company->language == 'sp') {echo "selected";}?>>Spanish</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="text-15">@lang("label.adPackage")<span>*</span></label>
                        <div class="select">
                            <select name="package_id" id="package_id" class="form-control required">
                                <option value="" disabled="" selected="">@lang("label.adSelect Package List")</option>
                                        @foreach ($packageList as $package)
                                        <option value="{{ $package->id }}" <?php if($company->package_name == $package->name) {echo "selected";}else{echo "disabled";}?>>{{ $package->name }}</option>
                                        @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="text-15">@lang('label.adCompany Name')<span>*</span></label>
                        <input type="text" name="company_name" id="company_name" value="{{$company->company_name}}" placeholder="@lang('label.adCompany Name')" class="form-control required">
                    </div>
                    <div class="form-group">
                        <label class="text-15">@lang("label.adCompany Description")</label>
                        <textarea name="company_description" id="company_description" placeholder="@lang('label.adCompany Description')" class="form-control">{{$company->description}}</textarea>
                    </div>
                    <div class="form-group">
                        <div class="blank">
                            <label class="check">
                                <p>@lang('label.adAllow Anonymous')</p>
                                <input type="checkbox" name="allow_anonymous" id="allow_anonymous" <?php if ($company->allow_anonymous == 1) {
    echo "checked";
} ?>>
                                <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="blank">
                            <label class="check">
                                <p>@lang('label.adAllow Admin')</p>
                                <input type="checkbox" name="allow_add_admin" id="allow_add_admin" <?php if ($company->allow_add_admin == 1) {
    echo "checked";
} ?>>
                                <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <p>@lang("label.adCompany Logo")<span>*</span></p>
                        <div class="upload-btn-wrapper">
                            <button class="upload-btn">@lang("label.Upload Files")</button>
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
                    <div class="form-group">
                        <div class="btn-wrap-div">
                            <input type="submit" name="save" id="save" class="st-btn" value="@lang('label.adSubmit')" />
                            <a href="{{ route('company.index') }}" class="st-btn">Back</a>
                        </div>
                    </div>
                    {!! Form::close() !!}    
                </div>
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