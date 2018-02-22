@extends('template.default')
<title>@lang("label.adDICO - Blog")</title>
@section('content')

<div id="page-content">
    <div id='wrap'>
        <div id="page-heading">
            <ol class="breadcrumb">
                <li><a href="{{ route('index') }}">@lang("label.adDashboard")</a></li>
                <li class="active">@lang("label.adBlog")</li>
            </ol>
            <h1>@lang("label.adBlog")</h1>
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
                                <h4 class="icon">@lang("label.adBlog")</h4>
                            </div>
                            <div class="panel-body">
                                <table class="table table-bordered table-striped" id="blockList">
                                    <thead>
                                        <tr>
                                            <th>#@lang("label.adID")</th>
                                            <th>@lang("label.adTitle")</th>
                                            <th>@lang("label.adDescription")</th>
                                            <th>@lang("label.adAction")</th>
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
