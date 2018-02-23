@extends('template.default')
<title>@lang('label.adDICO - Points')</title>
@section('content')

<div id="page-content">
    <div id='wrap'>
        <div id="page-heading">
            <ol class="breadcrumb">
                <li><a href="{{ url('/home') }}">@lang('label.adDashboard')</a></li>
                <li class="active">@lang('label.adPoints')</li>
            </ol>
            <h1>@lang('label.adPoints')</h1>
            <div class="options">
                <div class="btn-toolbar">
                    <div class="btn-group hidden-xs" style="display: none;">
                        <a href="{{ route('points.create') }}" class="btn btn-primary">@lang('label.adAdd New')</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <form method="POST" id="points-search-form" class="form-inline" role="form">
                            <div class="form-group">
                                <label for="name">@lang('label.adActivity')</label>
                                <input type="text" class="form-control" name="activity" id="activity" placeholder="@lang('label.adActivity')">
                            </div>
                            <button type="submit" class="btn btn-primary">@lang('label.adSearch')</button>
                        </form>
                        <table class="table table-bordered table-striped" id="points_table">
                            <thead>
                                <tr>
                                    <th>#@lang('label.adID')</th>
                                    <th>@lang('label.adActivity')</th>
                                    <th>@lang('label.adPoints')</th>
                                    <th>@lang('label.adNotes')</th>
                                    <th>@lang('label.adAction')</th>
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

    @section('javascript')
    <script type="text/javascript">

    </script>
    @endsection