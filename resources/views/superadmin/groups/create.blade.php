@extends('template.default')
@section('content')

    @if(session()->has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
        </div>
    @endif
    @if(session()->has('err_msg'))
        <div class="alert alert-danger">
            {{ session()->get('err_msg') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
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
                <div class="row">
                    <div class="col-md-6">
                        <h1>Create Group</h1>
                    </div>
                    <div class="col-md-6">
                        <div class="pull-right nopadding"><p style="float:right;"><a
                                        href="{{ url('/home') }}">Dashboard</a> > <a href="{{ route('group.index') }}">Group</a>
                                > Create Group</p></div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="panel panel-default">
                    <div class="panel panel-body">
                        <div class="row">
                            {{ Form::open([ 'name' => 'createUserGroup','route' => 'group.store' , 'id' => 'createUserGroup'] ) }}
                            <div class="form-group container">
                                <div class="col-md-6">
                                    <label for="group_name">Group name:*</label>
                                    <input type="text" name="group_name" id="group_name" class="form-control required"/>
                                </div>
                                <div class="col-md-6">
                                    <label for="group_description"> Group Description:* </label>
                                    <textarea name="group_description" id="group_description"
                                              class="form-control required"></textarea>
                                </div>
                            </div>
                            <div class="form-group container ">
                                <div class="col-md-3">
                                    <label for="company_listing">Company:* </label>
                                    <select name="company_listing" class="form-control required" id="company_listing">
                                        <option value="">Select Company:*</option>
                                        @foreach($companies as $company)
                                            <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="group_owner">Group owner:* </label>
                                    <select name="group_owner" id="group_owner" class="form-control required">
                                        <option value="">Select user</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="users_listing">Users:* </label>
                                    <select name="users_listing[]" id="users_listing" class="form-control required"
                                            multiple="multiple">
                                        <option value="">Select company first</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group container">
                                <div class="col-md-12">
                                    {{ Form::submit('Save',['class' => 'btn btn-success']) }}
                                </div>
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@stop