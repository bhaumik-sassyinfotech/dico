@extends('template.default')
<title>DICO - Settings</title>
@section('content')

@include('template.notification')

<div id="page-content">
    <div id='wrap'>
        <div id="page-heading">
            <ol class="breadcrumb">
                <li><a href="{{ url('/home') }}">Dashboard</a></li>              
                <li class="active">Settings</li>
            </ol>
            <h1 class="tp-bp-0">Settings </h1>
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
                                    <label class="text-15">Email 1<span>*</span></label>
                                    <input type="email" name="email1" id="email1"
                                           placeholder="Email 1"
                                           class="form-control required" value="{{$settings->email1}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-15">Email 2<span></span></label>
                                    <input type="email" name="email2" id="email2"
                                           placeholder="Email 2"
                                           class="form-control " value="{{$settings->email2}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-15">Support Email<span>*</span></label>
                                    <input type="email" name="support_email" id="support_email"
                                           placeholder="Support Email"
                                           class="form-control required" value="{{$settings->support_email}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-15">Copyright<span>*</span></label>
                                    <input type="text" name="copyright" id="copyright"
                                           placeholder="Copyright"
                                           class="form-control required" value="{{$settings->copyright}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-15">Phone<span></span></label>
                                    <input type="text" name="phone" id="phone" placeholder="Phone" class="form-control " value="{{$settings->phone}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-15">Mobile<span>*</span></label>
                                    <input type="number" name="mobile" id="mobile" placeholder="Mobile" class="form-control required" value="{{$settings->mobile}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-15">Facebook<span></span></label>
                                    <input type="text" name="facebook" id="facebook"
                                           placeholder="Facebook"
                                           class="form-control " value="{{$settings->facebook}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-15">Twitter<span></span></label>
                                    <input type="text" name="twitter" id="twitter"
                                           placeholder="Twitter"
                                           class="form-control " value="{{$settings->twitter}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-15">Instagram<span></span></label>
                                    <input type="text" name="instagram" id="instagram"
                                           placeholder="Instagram"
                                           class="form-control " value="{{$settings->instagram}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-15">Address<span></span></label>
                                    <input type="text" name="address" id="address"
                                           placeholder="Address"
                                           class="form-control " value="{{$settings->address}}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="btn-wrap-div">
                                <input type="submit" class="st-btn" value="Submit">                                
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
