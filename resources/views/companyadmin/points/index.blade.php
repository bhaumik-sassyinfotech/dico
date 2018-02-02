@extends('template.default')
<title>DICO - Points</title>
@section('content')

<div id="page-content">
    <div id='wrap'>
        <div id="page-heading">
            <ol class="breadcrumb">
                <li><a href="{{ url('/home') }}">Dashboard</a></li>
                <li class="active">Points</li>
            </ol>
            <h1>Points</h1>
            <div class="options">
                <div class="btn-toolbar">
                    <div class="btn-group hidden-xs" style="display: none;">
                        <a href="{{ route('points.create') }}" class="btn btn-primary">Add New</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <form method="POST" id="points-search-form" class="form-inline" role="form">
                            <div class="form-group">
                                <label for="name">Activity</label>
                                <input type="text" class="form-control" name="activity" id="activity" placeholder="Activity">
                            </div>
                            <button type="submit" class="btn btn-primary">Search</button>
                        </form>
                        <table class="table table-bordered table-striped" id="points_table">
                            <thead>
                                <tr>
                                    <th>#ID</th>
                                    <th>Activity</th>
                                    <th>Points</th>
                                    <th>Notes</th>
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

    @section('javascript')
    <script type="text/javascript">

    </script>
    @endsection