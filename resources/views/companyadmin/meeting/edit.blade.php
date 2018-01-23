@extends('template.default')
<title>DICO - Meeting</title>
@section('content')
    
    
    <div id="page-content" class="new-meeting-details" style="min-height: 943px;">
        <div id='wrap'>
            <div id="page-heading">
                <ol class="breadcrumb">
                    <li><a href="{{ url('/home') }}">Dashboard</a></li>
                    <li><a href="{{ route('meeting.index') }}">Meeting</a></li>
                    <li class="active">Update Meeting</li>
                </ol>
                <h1 class="tp-bp-0">Meeting</h1>
                <hr class="border-out-hr">
            </div>
            <div class="container">
                <div class="row">
                   <!-- <div id="company-from">-->
                    {!! Form::open(['method' => 'PUT', 'route' => ['meeting.update', Helpers::encode_url($meeting->id)],'enctype'=>'multipart/form-data', 'id' => 'meeting_edit_form' , 'class' => 'common-form']) !!}
                    <div class="col-sm-8" id="post-detail-left">    
                        <div class="form-group">
                            <label>Type</label>
                            <div class="check-wrap">
                                <label class="check">Private
                                    <input class="privacy_type" type="checkbox" {{ ($meeting->privacy == '1') ? 'checked':'' }} name="privacy[]" id="private" value="private">
                                    <span class="checkmark"></span>
                                </label>
                                <label class="check">Public
                                    <input class="privacy_type" type="checkbox" {{ ($meeting->privacy == '0') ? 'checked':'' }} name="privacy[]" id="public" value="public">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="text-15">Meeting Title<span>*</span></label>
                            <input type="text" name="meeting_title" id="meeting_title" value="{{$meeting->meeting_title}}" placeholder="Meeting title" class="form-control required">
                        </div>

                        <div class="form-group">
                            <label class="text-15">Meeting Description</label>
                            <textarea name="meeting_description" id="meeting_description" placeholder="Meeting Description" class="form-control">{{ nl2br($meeting->meeting_description) }}</textarea>
                        </div>

                        <div class="btn-wrap-div">
                        <input type="submit" class="st-btn" value="Submit">
                        <?php /*<div class="upload-btn-wrapper">
                            <button class="upload-btn">Upload Files</button>
                            <input type="file" name="file_upload" id="file_upload" class="file-upload__input">
                        </div>*/?>
                    </div>
                    </div>    
                    {!! Form::close() !!}
                   
                   <!-- </div>-->
                </div>
            </div>
        </div>
    </div>
@stop
@push('javascripts')
    <script type="text/javascript">
        $("#meeting_edit_form").validate({
            rules:{
                'meeting_title':{
                    required: true,
                }
            },
            submitHandler: function (form) {
                $('.save').prop('disabled',true);
                form.submit();
            }
        });
    </script>
@endpush