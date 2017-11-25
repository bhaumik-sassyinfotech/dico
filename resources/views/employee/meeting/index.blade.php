@extends('template.default')
@section('content')

    <div id="page-content">
        <div id='wrap'>
            <div id="page-heading">
                <ol class="breadcrumb">
                    <li><a href="{{ url('/home') }}">Dashboard</a></li>
                    <li class="active">Meeting</li>
                </ol>
                <h1>Meeting</h1>
                <div class="options">
                    <div class="btn-toolbar">
                        <a href="{{ route('meeting.create') }}" class="btn btn-primary">Add New</a>
                    </div>
                </div>
            </div>

            <div class="container">
                <div class="panel panel-default">
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
                        <table class="table table-bordered table-striped" id="meeting_table">
                            <thead>
                            <tr>
                                <th>#ID</th>
                                <th>Meeting title</th>
                                <th>Meeting description</th>
                                <th>Type</th>
                                <th>Total members</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop