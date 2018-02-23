@extends('template.default')
<title>@lang("label.adDICO - Company")</title>
@section('content')
<div id="page-content" class="create-user create-user-popup" style="min-height: 650px;">
    <div id='wrap'>
        <div id="page-heading">
            <ol class="breadcrumb">
                <li><a href="{{ route('index') }}">@lang("label.adDashboard")</a></li>
                <li><a href="{{ route('company.index') }}">@lang("label.adCompany")</a></li>
                <li class="active">@lang("label.adCreate Company")</li>
            </ol>
            <h1 class="tp-bp-0">@lang("label.adCompany")</h1>
            <hr class="border-out-hr">
        </div>
        <div class="container">
            <div class="row">
                <div id="company-from">
                    <form name="company_form" class="common-form" id="company_form" method="post" action="{{route('company.store')}}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label class="text-15">@lang("label.adLanguage")<span>*</span></label>
                            <div class="select">
                                <select name="language" id="language" class="form-control required">
                                    <option selected="" disabled="" value="">@lang('label.adSelect Language')</option>
                                    <option value="en">English</option>
                                    <option value="sp">Spanish</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                        <label class="text-15">@lang("label.adPackage")<span>*</span></label>
                        <div class="select">
                            <select name="package_id" id="package_id" class="form-control required">
                                <option value="" disabled="" selected="">Select Package List</option>
                                        @foreach ($packageList as $package)
                                        <option value="{{ $package->id }}">{{ $package->name }}</option>
                                        @endforeach
                            </select>
                        </div>
                    </div>
                        <div class="form-group">
                            <label class="text-15">@lang('label.adCompany Name')<span>*</span></label>
                            <input type="text" name="company_name" id="company_name" placeholder="@lang('label.adCompany Name')" class="form-control required">
                        </div>
                        <div class="form-group">
                            <label class="text-15">@lang("label.adCompany Description")</label>
                            <textarea name="company_description" id="company_description" placeholder="@lang('label.adCompany Description')" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <div class="blank">
                                <label class="check">
                                    <p>@lang('label.adAllow Anonymous')</p>
                                    <input type="checkbox" name="allow_anonymous" id="allow_anonymous">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="blank">
                                <label class="check">
                                    <p>@lang('label.adAllow Admin')</p>
                                    <input type="checkbox" name="allow_add_admin" id="allow_add_admin">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <p>@lang("label.adCompany Logo")<span>*</span></p>
                            <div class="upload-btn-wrapper">
                                <button class="upload-btn">@lang("label.adUpload Files")</button>
                                <input type="file" name="file_upload" id="file_upload" class="file-upload__input">
                            </div>
                        </div> 
                        <div class="form-group">
                            <div class="btn-wrap-div">
                                <input type="submit" name="save" id="save" class="st-btn" value="@lang('label.adSubmit')" />
                                <a href="{{ route('company.index') }}" class="st-btn">@lang('label.adBack')</a>
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
<script type="text/javascript">
</script>
@endsection
