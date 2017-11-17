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
    <?php $state = 'disabled'; ?>
    {!! Form::open(['method' => 'PUT', 'route' => ['group.update', $groupData->id], 'id' => 'group_users_edit_form']) !!}
    <div id="page-content">
        <div id='wrap'>
            <div id="page-heading">
                <ol class="breadcrumb">
                    <li><a href="{{ url('dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('group.index') }}">Group</a></li>
                    <li class="active">Update Group</li>
                </ol>
                <h1>Update Group</h1>
                <div class="options">
                    <div class="btn-toolbar">
                        <a href="{{ route('group.index') }}" class="btn btn-default">Back</a>
                        <input type="submit" name="save" id="save" class="btn btn-primary" value="Update">
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="panel panel-midnightblue">

                    <input type="hidden" id="group_id" value="{{ $groupId }}">
                    <input type="hidden" id="company_id" value="{{ $groupData->company_id }}">
                    <div class="panel-heading">
                        <h4>Update group details</h4>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="col-md-6">
                                <label for="group_name">Group name:*</label>
                                <input type="text" name="group_name" id="group_name" class="form-control required"
                                       value="{{ $groupData->group_name }}"/>
                            </div>
                            <div class="col-md-6">
                                <label for="group_description"> Group Description:</label>
                                <textarea name="group_description" id="group_description"
                                          class="form-control required">{{ nl2br($groupData->description) }}</textarea>
                            </div>
                        </div>
                        <br>

                        <div class="form-group">
                            <div class="col-md-6">
                                <label for="company_listing">Company:* </label>
                                <select name="company_listing" class="form-control required" {{ $state }}>
                                    <option value="">Select Company:*</option>
                                    <?php $groupCompanyId = $groupData->company_id; ?>
                                    @foreach($companies as $comp)
                                        <option {{ $comp->id == $groupCompanyId?'selected':'' }} value="{{ $comp->id }}">{{ $comp->company_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="company_users">Add Users</label>
                                <select name="company_users[]" id="company_users" class="form-control required" multiple="multiple">
                                    @foreach($companyEmployee as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="" style="width: 100%">&nbsp;</label>
                                <button id="add_user" class="btn btn-success">Add to group</button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                        <br>
                        <div class="form-group">
                            <div class="col-md-12 table-responsive">
                                <table class="table table-bordered table-striped" id="group_users_edit_table">
                                <thead>
                                <tr>
                                    <th>#ID</th>
                                    <th>Name</th>
                                    <th>Admin</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                            </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop