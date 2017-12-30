@extends('template.default')
<title>DICO - SecurityQuestion</title>
@section('content')

<div id="page-content">
    <div id='wrap'>
        <div id="page-heading">
            <ol class="breadcrumb">
                <li><a href="{{ url('/home') }}">Dashboard</a></li>
                <li class="active">Security Question</li>
            </ol>
            <h1>Security Question</h1>
            <div class="options">
                <div class="btn-toolbar">
                    <div class="btn-group hidden-xs">
                        <a href="{{ route('security_question.create') }}" class="btn btn-primary">Add New</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="panel panel-default">
                <div class="panel-body">
                   @include('template.notification')
                    <table class="table table-bordered table-striped" id="security_question_table">
                        <thead>
                        <tr>
                            <th>#ID</th>
                            <th>Security Question</th>
                            <th>Action</th>
                        </tr>
                        </thead>

                    </table>
                    <div class="col-lg-6"></div>
                    <div class="col-lg-6">
                        <div class="col-lg-6">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
