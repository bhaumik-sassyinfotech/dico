@extends('template.default')
<title>DICO - Packages</title>
@section('content')

<div id="page-content">
    <div id='wrap'>
        <div id="page-heading">
            <ol class="breadcrumb">
                <li><a href="{{ url('/home') }}">Dashboard</a></li>
                <li class="active">Packages List</li>
            </ol>
            <h1>Packages List</h1>
            <div class="options">
                <div class="btn-toolbar">
                    
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-12">
                    <div class="panel-body">
                            <div class="panel panel-info " style="overflow-x:auto;">
                            <div class="panel-heading trophy">
                                <h4 class="icon">Packages List</h4>
                            </div>
                            <div class="panel-body">
                            <table class="table table-bordered table-striped" id="packageList">
                                <thead>
                                <tr>
                                    <th>#ID</th>
                                    <th>Name</th>
                                    <th>Total user</th>
                                    <th>Amount</th>
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
