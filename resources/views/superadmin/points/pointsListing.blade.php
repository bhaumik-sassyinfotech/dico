
@extends('template.default')

@section('content')
<div id="page-content" class="point-page" style="min-height: 650px;">
    <div id="wrap">
        <div id="page-heading">
            <ol class="breadcrumb">
                <li><a href="{{ url('/home') }}">Dashboard</a></li>
                <li class="active">Points</li>
            </ol>
            <h1 class="tp-bp-0">Points</h1>
            <div style="display: none;" class="options">
                <div class="btn-toolbar">
                    <div class="btn-group hidden-xs">
                                       <div class="btn-group color-changes">
                                           <a data-toggle="dropdown" class="btn btn-default dropdown-toggle" href="#"><i aria-hidden="true" class="fa fa-filter fa-6"></i><span class="hidden-xs hidden-sm">Filter</span> </a>
                                                <ul class="dropdown-menu">
                                                    <li><a href="#">Notification off</a></li>
                                                    <li><a href="#">Edit Post</a></li>
                                                    <li><a href="#">Delete Post</a></li>
                                                </ul>
                                        </div>
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
                                  <li class="active"><a href="#threads" data-toggle="tab"><i class="fa fa-list visible-xs icon-scale"></i><span class="hidden-xs">All Users</span></a></li>
                                  <li style="display: none;" class=""><a href="#users" data-toggle="tab"><i class="fa fa-group visible-xs icon-scale"></i><span class="hidden-xs">My Group Users</span></a></li>
                                </ul>
                              </h4>
                       </div>
                        <div class="panel-body">
                            <div class="tab-content">
                                <div tabindex="5000" style="overflow-y: hidden;" class="tab-pane active" id="threads">
                                     <div class="container">

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="panel panel-info ">
                                                <div class="panel-heading trophy">
                                                    <h4 class="icon">Users List</h4>
                                                </div>
                                                <div class="panel-body">
                                                    <div class="row">
                                                    <table class="table" id="points-listing">
                                                        <thead>
                                                            <tr>
                                                                <th class="p-font p-user"><img src="{{ asset(IMAGE_PATH.'/p-icon.png') }}" class="mr-10">User Name</th>
                                                                <th class="p-font p-idea"><img src="{{ asset(IMAGE_PATH.'/p-light.png') }}" class="mr-10"> Idea</th>
                                                                <th class="p-font p-question"><img src="{{ asset(IMAGE_PATH.'/p-help.png') }}" class="mr-10">Questions</th>
                                                                <th class="p-font p-approve"><img src="{{ asset(IMAGE_PATH.'/p-approve.png') }}" class="mr-10">Approved</th>
                                                                <th class="p-font p-answer"><img src="{{ asset(IMAGE_PATH.'/p-answer.png') }}" class="mr-10">Answers</th>
                                                                <th class="p-font p-solution"><img src="{{ asset(IMAGE_PATH.'/p-solution.png') }}" class="mr-10">Solutions</th>
                                                                <th class="p-font p-comments"><img src="{{ asset(IMAGE_PATH.'/p-comment.png') }}" class="mr-10">Comments</th>
                                                                <th class="p-font p-like"><img src="{{ asset(IMAGE_PATH.'/p-like.png') }}" class="mr-10">Likes</th>
                                                                <th class="p-font p-Total"><img src="{{ asset(IMAGE_PATH.'/p-total.png') }}" class="mr-10">Total</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody></tbody>
                                                            </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    </div>
                                </div>

                                <div tabindex="5002" style="overflow-y: hidden;" class="tab-pane" id="users">
                                       <div class="container">

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="panel panel-info ">
                                                <div class="panel-heading trophy">
                                                    <h4 class="icon">Group List</h4>
                                                </div>
                                                <div class="panel-body">
                                                    <div class="row">
                                                    <table class="table">
                                                                <thead>
                                                                   <tr>
                                                                        <th class="p-font p-user"><img src="assets/img/p-icon.png" class="mr-10">User Name</th>
                                                                        <th class="p-font p-idea"><img src="assets/img/p-light.png" class="mr-10"> Idea</th>
                                                                        <th class="p-font p-question"><img src="assets/img/p-help.png" class="mr-10">Questions</th>
                                                                        <th class="p-font p-approve"><img src="assets/img/p-approve.png" class="mr-10">Approved</th>
                                                                        <th class="p-font p-answer"><img src="assets/img/p-answer.png" class="mr-10">Answers</th>
                                                                        <th class="p-font p-solution"><img src="assets/img/p-solution.png" class="mr-10">Solutions</th>
                                                                        <th class="p-font p-comments"><img src="assets/img/p-comment.png" class="mr-10">Comments</th>
                                                                        <th class="p-font p-like"><img src="assets/img/p-like.png" class="mr-10">Likes</th>
                                                                        <th class="p-font p-Total"><img src="assets/img/p-total.png" class="mr-10">Total</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td><a>Jason Durelo</a></td>
                                                                        <td><p>234</p></td>
                                                                        <td><p>150</p></td>
                                                                        <td><p>68</p></td>
                                                                        <td><p>148</p></td>
                                                                        <td><p>258</p></td>
                                                                        <td><p>48</p></td>
                                                                        <td><p>25</p></td>
                                                                        <td><p>685</p></td>
                                                                    </tr>
                                                                </tbody>
                                                                <tbody>
                                                                    <tr>
                                                                        <td><a>Mary Jane</a></td>
                                                                        <td><p>234</p></td>
                                                                        <td><p>150</p></td>
                                                                        <td><p>68</p></td>
                                                                        <td><p>148</p></td>
                                                                        <td><p>258</p></td>
                                                                        <td><p>48</p></td>
                                                                        <td><p>25</p></td>
                                                                        <td><p>685</p></td>
                                                                    </tr>
                                                                </tbody>
                                                                <tbody>
                                                                    <tr>
                                                                        <td><a>Mike Tyson</a></td>
                                                                        <td><p>234</p></td>
                                                                        <td><p>150</p></td>
                                                                        <td><p>68</p></td>
                                                                        <td><p>148</p></td>
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>


                        </div>
                    </div>
                </div>
@stop