@extends('template.default')
<title>@lang('label.adDICO - Points')</title>
@section('content')

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
<div id="page-content">
    <div id='wrap'>
        <div id="page-heading">
            <ol class="breadcrumb">
                <li><a href="{{ url('/home') }}">@lang('label.adDashboard')</a></li>
                <li><a href="{{ route('points.index') }}">@lang('label.adPoints')</a></li>
                <li class="active">@lang('label.adCreate Points')</li>
            </ol>
            <h1>@lang('label.adPoints')</h1>
        </div>
        <div class="container">
            <div class="panel panel-default">
                <form name="points_form" id="points_form" method="post" action="{{route('points.store')}}">
                     {{ csrf_field() }}
                    <div class="new_button">
                        <div class="pull-right extra_button">
                            <input type="submit" name="save" id="save" class="btn btn-primary" value="@lang('label.adSubmit')">
                        </div>
                        <div class="pull-right extra_button">
                                <a href="{{ route('points.index') }}" class="btn btn-default" >@lang('label.adBack')</a>
                        </div>
                        <div style="clear: both;"></div>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 form-group">
                                <label>@lang('label.adActivity<span>*</span></label>
                                <input type="text" name="activity" id="activity" placeholder="@lang('label.adActivity')" class="form-control required">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 form-group">
                                <label>@lang('label.adPoints')<span>*</span></label>
                                <input type="text" name="points" id="points" placeholder="@lang('label.adPoints')" class="form-control required">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 form-group">
                                <label>@lang('label.adNotes')</label>
                                <textarea name="notes" id="notes" placeholder="@lang('label.adNotes')" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                </form>     
            </div>
        </div>
    </div>
</div>
@stop
@section('javascript')
<script type="text/javascript">
</script>
@endsection
