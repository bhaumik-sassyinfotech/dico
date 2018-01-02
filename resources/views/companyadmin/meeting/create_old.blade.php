@extends('template.default')
@section('content')

    @if ($errors->any())
        <div class="alert alert-dismissable alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div id="page-content">
        <div id='wrap'>
            <div id="page-heading">
                <ol class="breadcrumb">
                    <li><a href="{{ url('/home') }}">Dashboard</a></li>
                    <li><a href="{{ route('meeting.index') }}">Meeting</a></li>
                    <li class="active">Create Meeting</li>
                </ol>
                <h1>Create Meeting</h1>
            </div>
            {{ Form::open([ 'name' => 'createMeeting','route' => 'meeting.store' , 'id' => 'createMeeting'] ) }}
            <div class="container">
                <div class="panel panel-default">
                    {{--<div class="panel-heading">--}}
                    {{--<h4>Create Group</h4>--}}
                    {{--</div>--}}
                    <div class="panel-body">
                        @if(session()->has('success'))
                            <div class="alert alert-dismissable alert-success">
                                {{ session()->get('success') }}
                            </div>
                        @endif
                        @if(session()->has('err_msg'))
                            <div class="alert alert-dismissable alert-danger">
                                {{ session()->get('err_msg') }}
                            </div>
                        @endif
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="meeting_title" class="control-label">Meeting title:*</label>
                                    <input type="text" name="meeting_title" id="meeting_title" class="form-control required"/>
                                </div>
                                <div class="col-md-6">
                                    <label class="control-label" for="meeting_description"> Meeting description: </label>
                                    <textarea name="meeting_description" id="meeting_description"
                                              class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="company_id" value="{{ $company_id }}">
                        <div class="form-group ">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="employees_listing">Groups and Employees: </label>
                                    <select name="employees[]" id="employees_listing" class="form-control" multiple="multiple">
                                        {{--<option value="">Select employees</option>--}}
                                        <optgroup label="Employees">
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                        @endforeach
                                        </optgroup>
                                        <optgroup label="Groups">
                                            @foreach($groups as $group)
                                                <option value="group_{{ $group->id }}">{{ $group->group_name }}</option>
                                            @endforeach
                                        </optgroup>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="" style="width:100%;">&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                    <label class="checkbox-inline"><input class="privacy_type" type="checkbox" name="privacy[]" id="public" value="public">Public</label>
                                </div>
                                <div class="col-md-2">
                                    <label for="" style="width:100%;">&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                    <label class="checkbox-inline"><input class="privacy_type" type="checkbox" name="privacy[]" id="private" value="private">Private</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="btn-toolbar">
                                    <a class="btn btn-default" href="{{ route('meeting.index') }}">Back</a>
                                    {{ Form::submit('Submit',['class' => 'btn btn-primary']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
@stop