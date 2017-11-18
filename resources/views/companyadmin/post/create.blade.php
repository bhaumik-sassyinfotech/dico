@extends('template.default')
<title>DICO - Post</title>
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
                <li><a href="{{ route('post.index') }}">Post</a></li>
                <li class="active">Create Post</li>
            </ol>
            <h1>Post</h1>
            <?php /*<div>
                <div class="col-md-6 pull-right nopadding"><p style="float:right;"><a href="{{ url('/home') }}">Dashboard</a> > <a href="{{ route('user.index') }}">User</a> > Create User</p></div>
            </div>*/?>
        </div>
        <div class="container">
            <div class="panel panel-default">
                <form name="post_form" id="post_form" method="post" action="{{route('post.store')}}">
                     {{ csrf_field() }}
                    <div class="new_button">
                        <div class="pull-right extra_button">
                            <input type="submit" name="save" id="save" class="btn btn-primary">
                        </div>
                        <div class="pull-right extra_button">
                                <a href="{{ route('post.index') }}" class="btn btn-default" >Back</a>
                        </div>
                        <div style="clear: both;"></div>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 form-group">
                                <label>Post<span>*</span></label><br>
                                <div class="col-xs-4 form-group"><input type="checkbox" name="post_type" id="post_type_idea" value="idea"> Idea</div>
                                <div class="col-xs-4 form-group"><input type="checkbox" name="post_type" id="post_type_question" value="question"> Question</div>
                                <div class="col-xs-4 form-group"><input type="checkbox" name="post_type" id="post_type_challenges" value="challenges"> Challenges</div>
                                <div id="err_post_type"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 form-group">
                                <label>Post Title<span>*</span></label>
                                <input type="text" name="post_title" id="post_title" placeholder="Post Title" class="form-control required">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 form-group">
                                <label>Post Description</label>
                                <textarea name="post_description" id="post_description" placeholder="Post Description" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 form-group">
                                <label>Is Anonymous</label><br/>
                                <input type="checkbox" name="is_anonymous" id="is_anonymous">
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
