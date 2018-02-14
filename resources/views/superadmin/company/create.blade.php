@extends('template.default')
<title>DICO - Company</title>
@section('content')
<div id="page-content" class="create-user create-user-popup" style="min-height: 650px;">
    <div id='wrap'>
        <div id="page-heading">
            <ol class="breadcrumb">
                <li><a href="{{ url('/home') }}">Dashboard</a></li>
                <li><a href="{{ route('company.index') }}">Company</a></li>
                <li class="active">Create Company</li>
            </ol>
            <h1 class="tp-bp-0">Company</h1>
            <hr class="border-out-hr">
        </div>
        <div class="container">
            <div class="row">
                <div id="company-from">
                    <form name="company_form" class="common-form" id="company_form" method="post" action="{{route('company.store')}}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label class="text-15">Language<span>*</span></label>
                            <div class="select">
                                <select name="language" id="language" class="form-control required">
                                    <option selected="" disabled="" value="">Select Language</option>
                                    <option value="en">English</option>
                                    <option value="sp">Spanish</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                        <label class="text-15">Package<span>*</span></label>
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
                            <label class="text-15">Company Name<span>*</span></label>
                            <input type="text" name="company_name" id="company_name" placeholder="Company Name" class="form-control required">
                        </div>
                        <div class="form-group">
                            <label class="text-15">Company Description</label>
                            <textarea name="company_description" id="company_description" placeholder="Company Description" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <div class="blank">
                                <label class="check">
                                    <p>Allow Anonymous</p>
                                    <input type="checkbox" name="allow_anonymous" id="allow_anonymous">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="blank">
                                <label class="check">
                                    <p>Allow Admin</p>
                                    <input type="checkbox" name="allow_add_admin" id="allow_add_admin">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <p>Company Logo<span>*</span></p>
                            <div class="upload-btn-wrapper">
                                <button class="upload-btn">Upload Files</button>
                                <input type="file" name="file_upload" id="file_upload" class="file-upload__input">
                            </div>
                        </div> 
                        <div class="form-group">
                            <div class="btn-wrap-div">
                                <input type="submit" name="save" id="save" class="st-btn" value="Submit" />
                                <a href="{{ route('company.index') }}" class="st-btn">Back</a>
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
