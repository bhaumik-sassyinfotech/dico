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
    <div id="page-content">
        <div id='wrap'>
            <div id="page-heading">
                <ol class="breadcrumb">
                    <li><a href="{{ url('/home') }}">Dashboard</a></li>
                    <li class="active">Group</li>
                </ol>
                <h1>Group</h1>
                <div class="options">
                    <div class="btn-toolbar">
                        <div class="btn-group hidden-xs">
                            <a href="{{ route('group.create') }}" class="btn btn-primary">Add New</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <table class="table table-bordered table-striped" id="group_table">
                            <thead>
                                <tr>
                                    <th>#ID</th>
                                    <th>Group Name</th>
                                    <th>Group Description</th>
                                    <th>Total members</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
@stop