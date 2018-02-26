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
                <li><a href="{{ route('index') }}">@lang('label.adDashboard')</a></li>
                <li><a href="{{ route('points.index') }}">@lang('label.adPoints')</a></li>
                <li class="active">@lang('label.adUpdate Points')</li>
            </ol>
            <h1 class="tp-bp-0">@lang('label.adPoints')</h1>
            <hr class="border-out-hr">
        </div>
        <div class="container">

            <div class="row">
                <div id="company-from">
                    <div class="panel panel-default">
                    {!! Form::model($point, ['method' => 'PUT','class'=>'common-form', 'route' => ['points.update', $point->id],'enctype'=>'multipart/form-data', 'id' => 'points_form']) !!}

                           
                                <div class=" form-group">
                                    <label>@lang('label.adActivity')<span>*</span></label>
                                    <input type="text" name="activity" id="activity" value="{{$point->activity}}" placeholder="@lang('label.adActivity')" class="form-control required">
                                </div>
                       
                                <div class=" form-group">
                                    <label>@lang('label.adPoints')<span>*</span></label>
                                    <input type="text" name="points" id="points" value="{{$point->points}}" placeholder="@lang('label.adPoints')" class="form-control required">
                                </div>
                          
                                <div class="form-group">
                                    <label>@lang('label.adNotes')</label>
                                    <textarea name="notes" id="notes" placeholder="@lang('label.adNotes')" class="form-control">{{$point->notes}}</textarea>
                                </div>
                            
                        <div class=" form-group">
                            <div class="btn-wrap-div">
                        <a href="{{ route('points.index') }}" class="st-btn btn-default" >@lang('label.adBack')</a>
                        <input type="submit" name="save" id="save" class="st-btn btn-primary" value="@lang('label.adSubmit')">
                            </div></div>
                                
                   <!-- </form>     -->
                   {!! Form::close() !!}
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