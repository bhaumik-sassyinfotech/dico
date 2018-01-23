@extends('template.default')
@section('content')

    <div id="page-content" class="point-page">
        <div id='wrap'>
            <div id="page-heading">
                <ol class="breadcrumb">
                    <li><a href="{{ url('/home') }}">Dashboard</a></li>
                    <li class="active">Group</li>
                </ol>
                <h1 class="tp-bp-0">Group</h1>
                <div class="options" style="display: none;">
                    <div class="btn-toolbar">
                        <div class="btn-group hidden-xs">
                            <a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown"><i
                                        class="fa fa-filter fa-6" aria-hidden="true"></i><span
                                        class="hidden-xs hidden-sm hidden-md">Filter</span> </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel panel-midnightblue group-tabs">
                            <div class="panel-heading">
                                <h4>
                                    <ul class="nav nav-tabs">
                                        <li class="active"><a href="#threads" data-toggle="tab"><i
                                                        class="fa fa-list visible-xs icon-scale"></i><span
                                                        class="hidden-xs">All Groups</span></a></li>
                                        <li class=""><a href="#users" data-toggle="tab"><i
                                                        class="fa fa-group visible-xs icon-scale"></i><span
                                                        class="hidden-xs">My Group Users</span></a></li>
                                    </ul>
                                </h4>
                                <div class="btn-group top-set">
                                    <form method="post" class="search-form">
                                        <input placeholder="Search User" type="text">
                                        <input value="#" class="search-icon" type="button">
                                    </form>
                                    <button id="GoList" class="grid-view active">
                                        <img src="assets/img/icon/group-list.png" alt="group" class="block">
                                        <img src="assets/img/icon/group-lis-hover.png" alt="group" class="none" style="display:none">
                                    </button>
                                    <button id="GoGrid" class="grid-view">
                                        <img src="assets/img/icon/grid-view.png" alt="group list" class="block">
                                        <img src="assets/img/icon/grid-view-hover.png" alt="group list" class="none" style="display:none">

                                    </button>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="tab-content">
                                    <div tabindex="5000" class="tab-pane active"
                                         id="threads">
                                        <div class="container">

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="panel panel-info " style="overflow-x:auto;">
                                                        <div class="panel-heading trophy">
                                                            <h4 class="icon">Users List</h4>
                                                            <div class="pull-right">
                                                                <a href="#"><img src="{{ asset('assets/img/settings-icon.png') }}" alt="settings"></a>
                                                            </div>
                                                        </div>
                                                        <div class="panel-body">
                                                            <div class="row">
                                                                <table class="table">
                                                                    <thead>
                                                                    <tr>
                                                                        <th>Group Name</th>
                                                                        <th>Group Description</th>
                                                                        <th>Total Posts</th>
                                                                        <th>Total Members</th>
                                                                        <th>Actions</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <tr>
                                                                        <td><a>Jason Durelo</a></td>
                                                                        <td><p>48</p></td>
                                                                        <td><p>48</p></td>
                                                                        <td><p>25</p></td>
                                                                        <td><p>685</p></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><a>Mary Jane</a></td>
                                                                        <td><p>234</p></td>
                                                                        <td><p>150</p></td>
                                                                        <td><p>68</p></td>
                                                                        <td><p>685</p></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><a>Mike Tyson</a></td>
                                                                        <td><p>258</p></td>
                                                                        <td><p>48</p></td>
                                                                        <td><p>25</p></td>
                                                                        <td><p>685</p></td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div tabindex="5002" class="tab-pane" id="users">
                                        <div class="container">

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="panel panel-info " style="overflow-x: auto;" >
                                                        <div class="panel-heading trophy">
                                                            <h4 class="icon">Group List</h4>
                                                            <div class="pull-right">
                                                                <a href="#"><img src="{{ asset('assets/img/settings-icon.png') }}" alt="settings"></a>
                                                            </div>
                                                        </div>
                                                        <div class="panel-body">
                                                            <div class="row">
                                                                <table class="table" id="group_table">
                                                                    <thead>
                                                                    <tr>
                                                                        <th>Group Name</th>
                                                                        <th>Group Description</th>
                                                                        <th>Total Posts</th>
                                                                        <th>Total Members</th>
                                                                        <th>Actions</th>
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop