@extends('template.default')
<title>DICO - Group</title>
@section('content')
<div id="page-content" class="create-user create-user-popup package-page">
                   <div id='wrap'>
                        <div id="page-heading">
                            <ol class="breadcrumb">
                    <li><a href="{{ url('/home') }}">Dashboard</a></li>
                    <li><a href="{{ route('group.index') }}">Group</a></li>
                    <li class="active">Create Group</li>
                </ol>
                            <h1 class="tp-bp-0">Create Group</h1>
                            <hr class="border-out-hr">

                        </div>
                    <div class="container">
                        <div class="row">
                       <div>
                                {{ Form::open([ 'name' => 'createUserGroup', 'class' => 'common-form' ,'route' => 'group.store' , 'id' => 'createUserGroup','enctype'=>'multipart/form-data'] ) }}
                                    <div class="package-left">
                                    <div class="form-group">
                                        <label class="text-15" for="group_name">Group name*:</label>
                                        <input type="text" name="group_name" id="group_name" placeholder="Group name" class="required" value=" {{ old('group_name') }}"/>
                                     </div>

                                     <div class="form-group">
                                        <label class="text-15" for="group_description"> Group Description: </label>
                                        <textarea name="group_description" id="group_description" placeholder="Group description" class="form-control">{{ old('group_description') }}</textarea>
                                     </div>

                                     <div class="form-group">
                                        <label class="text-15" for="company_listing">Company*: </label>

                                    <div class="select">
                                        <select name="company_listing" class="required" id="company_listing">
                                            <option value="">Select Company</option>
                                                @if(!is_null(old('company_listing')))
                                                    <?php $company_id = old('company_listing');?>
                                                    @foreach($companies as $company)
                                                        <option {{ $company_id == $company->id ? 'selected' : '' }} value="{{ $company->id }}">{{ $company->company_name }}</option>
                                                    @endforeach
                                                @else
                                                    @foreach($companies as $company)
                                                        <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                                                    @endforeach
                                                @endif
                                        </select>
                                    </div>
                                     </div>
                                     <div class="form-group">
                                        <label class="text-15" for="group_owner">Group owner*: </label>
                                        <div class="select">
                                            <select name="group_owner" id="group_owner" class="required">
                                                <option value="">Select user</option>
                                            </select>
                                        </div>
                                     </div>
                                     <div class="form-group">
                                        <label class="text-15" for="users_listing" >Users*: </label>
                                        <div class="select">
                                            <select name="users_listing[]" id="users_listing" class="required"
                                                multiple="multiple" placeholder="Select company first" >
                                        </select>
                                        </div>
                                     </div>
                                     <div class="form-group">
                                         <div class="btn-wrap-div">
                                              <input type="submit" name="submit" class="st-btn">
                                              <a class="st-btn" href="{{ route('group.index') }}">Back</a>
                                         <!-- <input type="reset" class="st-btn" value="Cancel"> -->
                                         </div>
                                     </div>
                                    </div>
                                    <div class="package-right">
                                        <div class="form-group">
                                            <label class="text-15" for="group_logo">Group Logo:</label>
                                            <div class="upload-btn-wrapper">
                                                <button class="upload-btn">Upload Files</button>
                                                <input type="file" name="file_upload" id="file_upload" class="file-upload__input" value="" onchange="readURL(this)">
                                            </div>
                                        </div>
                                        <div class="preview-box">
                                            <?php
                                                $group_logo = DEFAULT_GROUP_IMAGE;
                                            ?>
                                            <img src="{{asset($group_logo)}}" id="user-profile" height="135" width="135">
                                        </div>

                                    </div>    
                                 {{ Form::close() }}


                             </div>
                            </div>
                        </div>

                     </div>
        </div> <!-- container -->
@endsection
@push('javascripts')
<script type="text/javascript">        
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#user-profile').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush