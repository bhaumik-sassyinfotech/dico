@extends('template.default')
<title>DICO - SecurityQuestion</title>
@section('content')

<div id="page-content" class="user-table">
    <div id='wrap'>
        <div id="page-heading">
            <ol class="breadcrumb">
                <li><a href="{{ url('/home') }}">Dashboard</a></li>
                <li class="active">Security Question</li>
            </ol>
            <h1 class="tp-bp-0">Security Question</h1>
            <div class="options">
                <div class="btn-toolbar">
                    <a class="btn btn-default" href="{{ route('security_question.create') }}">
                        <i aria-hidden="true" class="fa fa-pencil-square-o fa-6"></i>
                        <span class="hidden-xs hidden-sm">Add New</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-12">
                    <div class="panel-body">
                            <div class="panel panel-info " style="overflow-x:auto;">
                            <div class="panel-heading trophy">
                                <h4 class="icon">Security Question List</h4>
                            </div>
                            <div class="panel-body">
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
        </div>
    </div>
</div>
@stop
