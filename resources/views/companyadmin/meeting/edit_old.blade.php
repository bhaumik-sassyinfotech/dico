@extends('template.default')
<title>DICO - Meeting</title>
@section('content')
    
    
    <div id="page-content">
        <div id='wrap'>
            <div id="page-heading">
                <ol class="breadcrumb">
                    <li><a href="{{ url('/home') }}">Dashboard</a></li>
                    <li><a href="{{ route('meeting.index') }}">Meeting</a></li>
                    <li class="active">Update Meeting</li>
                </ol>
                <h1>Meeting</h1>
            </div>
            <div class="container">
                <div class="panel panel-default">
                    @include('template.notification')
                    {!! Form::open(['method' => 'PUT', 'route' => ['meeting.update', Helpers::encode_url($meeting->id)],'enctype'=>'multipart/form-data', 'id' => 'meeting_edit_form']) !!}
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 form-group">
                                <label>Meeting Title<span>*</span></label>
                                <input type="text" name="meeting_title" id="meeting_title" value="{{$meeting->meeting_title}}" placeholder="Meeting title" class="form-control required">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 form-group">
                                <label>Meeting Description</label>
                                <textarea name="meeting_description" id="meeting_description" placeholder="Meeting Description" class="form-control">{{ nl2br($meeting->meeting_description) }}</textarea>
                            </div>
                        </div>
                        <div class="formgroup">
                            <div class="row">
                                <div class="col-xs-6">
                                    <span class="btn btn-primary fileinput-button">
                                        <i class="fa fa-upload"></i>
                                        <span>upload</span>
                                        <input type="file" name="file_upload" id="file_upload" class="file-upload__input">
                                        <?php
                                        if( isset($meeting['postAttachment']) )
                                        {
                                            echo $meeting['postAttachment']['file_name'];
                                        }
                                        ?>
                                    </span>
                                </div>
                                <div class="col-xs-6 row">
                                    <div class="col-md-6">
                                        <label for="" style="width:100%;">&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                        <label class="checkbox-inline"><input class="privacy_type" type="checkbox" {{ ($meeting->privacy == '0') ? 'checked':'' }} name="privacy[]" id="public" value="public">Public</label>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="" style="width:100%;">&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                        <label class="checkbox-inline"><input class="privacy_type" type="checkbox" {{ ($meeting->privacy == '1') ? 'checked':'' }} name="privacy[]" id="private" value="private">Private</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="row col-xs-12">
                            <div class="btn-toolbar">
                                <a href="{{ route('meeting.index') }}" class="btn btn-default">Back</a>
                                <input type="submit" name="save" id="save" value="Submit" class="btn btn-primary save">
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
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