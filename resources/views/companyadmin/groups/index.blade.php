@extends('template.default')
@section('content')


    <div id="page-content">
        <div id='wrap'>
            <div id="page-heading">
                <ol class="breadcrumb">
                    <li><a href="{{ url('/home') }}">Dashboard</a></li>
                    <li class="active">Group</li>
                </ol>
                <h1>Group</h1>
                
            </div>

            <div class="container">
                <div class="panel panel-default">
                    <div class="panel-body">
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