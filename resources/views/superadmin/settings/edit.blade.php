@extends('template.default')
<title>@lang('label.adDICO - Settings')</title>
@section('content')

@include('template.notification')

<div id="page-content">
    <div id='wrap'>
        <div id="page-heading">
            <ol class="breadcrumb">
                <li><a href="{{ route('index') }}">@lang('label.adDashboard')</a></li>              
                <li class="active">@lang('label.adSettings')</li>
            </ol>
            <h1 class="tp-bp-0">@lang('label.adSettings') </h1>
            <hr class="border-out-hr">

        </div>
        <div class="container">
            <div class="row">
                <div id="create-user-from" style="width: 100%;padding: 10px" class="setting-page">
                    <form name="SettingsEdit" id="SettingsEdit" method="POST" action="{{ route('settings.update',[$settings->id]) }}" class="common-form" >
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-15">@lang('label.adEmail 1')<span>*</span></label>
                                    <input type="email" name="email1" id="email1"
                                           placeholder="@lang('label.adEmail 1')"
                                           class="form-control required" value="{{$settings->email1}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-15">@lang('label.adEmail 2')<span></span></label>
                                    <input type="email" name="email2" id="email2"
                                           placeholder="@lang('label.adEmail 2')"
                                           class="form-control " value="{{$settings->email2}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-15">@lang('label.adSupport Email')<span>*</span></label>
                                    <input type="email" name="support_email" id="support_email"
                                           placeholder="@lang('label.adSupport Email')"
                                           class="form-control required" value="{{$settings->support_email}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-15">@lang('label.adCopyright')<span>*</span></label>
                                    <input type="text" name="copyright" id="copyright"
                                           placeholder="@lang('label.adCopyright')"
                                           class="form-control required" value="{{$settings->copyright}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-15">@lang('label.adPhone')<span></span></label>
                                    <input type="text" name="phone" id="phone" placeholder="@lang('label.adPhone')" class="form-control " value="{{$settings->phone}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-15">@lang('label.adMobile')<span>*</span></label>
                                    <input type="number" name="mobile" id="mobile" placeholder="@lang('label.adMobile')" class="form-control required" value="{{$settings->mobile}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-15">@lang('label.adFacebook')<span></span></label>
                                    <input type="text" name="facebook" id="facebook"
                                           placeholder="@lang('label.adFacebook')"
                                           class="form-control " value="{{$settings->facebook}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-15">@lang('label.adTwitter')<span></span></label>
                                    <input type="text" name="twitter" id="twitter"
                                           placeholder="@lang('label.adTwitter')"
                                           class="form-control " value="{{$settings->twitter}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-15">@lang('label.adInstagram')<span></span></label>
                                    <input type="text" name="instagram" id="instagram"
                                           placeholder="@lang('label.adInstagram')"
                                           class="form-control " value="{{$settings->instagram}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-15">@lang('label.adAddress')<span></span></label>
                                    <input type="text" name="address" id="address"
                                           placeholder="@lang('label.adAddress')"
                                           class="form-control " value="{{$settings->address}}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="btn-wrap-div">
                                <input type="submit" class="st-btn" value="@lang('label.adSubmit')">                                
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
