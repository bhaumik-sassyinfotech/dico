@extends('template.default')
<title>DICO - Points</title>
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
                <li><a href="{{ url('/home') }}">Dashboard</a></li>
                <li><a href="{{ route('points.index') }}">Points</a></li>
                <li class="active">Update Points</li>
            </ol>
            <h1>Points</h1>
        </div>
        <div class="container">
            <div class="panel panel-default">
                {!! Form::model($point, ['method' => 'PUT', 'route' => ['points.update', $point->id],'enctype'=>'multipart/form-data', 'id' => 'points_form']) !!}
                    
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 form-group">
                                <label>Activity<span>*</span></label>
                                <input type="text" name="activity" id="activity" value="{{$point->activity}}" placeholder="Activity" class="form-control required">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 form-group">
                                <label>Points<span>*</span></label>
                                <input type="text" name="points" id="points" value="{{$point->points}}" placeholder="Points" class="form-control required">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 form-group">
                                <label>Notes</label>
                                <textarea name="notes" id="notes" placeholder="Notes" class="form-control">{{$point->notes}}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="row col-xs-12">
                            <div class="btn-toolbar">
                                <a href="{{ route('points.index') }}" class="btn btn-default" >Back</a>
                                <input type="submit" name="save" id="save" class="btn btn-primary">
                            </div>
                        </div>
                        <div style="clear: both;"></div>
                    </div>
               <!-- </form>     -->
               {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@stop
@section('javascript')
<script type="text/javascript">
</script>
@endsection