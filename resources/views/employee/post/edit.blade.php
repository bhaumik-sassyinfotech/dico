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
                <li class="active">Update Post</li>
            </ol>
            <h1>Post</h1>
        </div>
        <div class="container">
            <div class="panel panel-default">
                {!! Form::model($post, ['method' => 'PUT', 'route' => ['post.update', $post->id],'enctype'=>'multipart/form-data', 'id' => 'post_form']) !!}
                    <div class="new_button">
                        {{ csrf_field() }}
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
                                <div class="col-xs-4 form-group"><input type="checkbox" name="post_type" id="post_type_idea" value="idea" <?php if($post->post_type == 'idea') { echo "checked"; } ?>> Idea</div>
                                <div class="col-xs-4 form-group"><input type="checkbox" name="post_type" id="post_type_question" value="question" <?php if($post->post_type == 'question') { echo "checked"; } ?>> Question</div>
                                <div class="col-xs-4 form-group"><input type="checkbox" name="post_type" id="post_type_challenges" value="challenges" <?php if($post->post_type == 'challenges') { echo "checked"; } ?>> Challenges</div>
                                <div id="err_post_type"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 form-group">
                                <label>Post Title<span>*</span></label>
                                <input type="text" name="post_title" id="post_title" value="{{$post->post_title}}" placeholder="Post Title" class="form-control required">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 form-group">
                                <label>Post Description</label>
                                <textarea name="post_description" id="post_description" placeholder="Post Description" class="form-control">{{$post->post_description}}</textarea>
                            </div>
                        </div>
                        <?php
                            if(isset($company) && $company->allow_anonymous == 1) {
                        ?>
                        <div class="row">
                            <div class="col-xs-12 form-group">
                                <label>Is Anonymous</label><br/>
                                <input type="checkbox" name="is_anonymous" id="is_anonymous" <?php if($post->is_anonymous == 1) { echo "checked"; } ?>>
                            </div>
                        </div>
                            <?php } ?>
                        <div class="row">
                            <div class="col-xs-12 form-group">
                                <span class="btn btn-primary fileinput-button">
                                    <i class="fa fa-upload"></i>
                                    <span>upload</span>
                                    <input type="file" name="file_upload" id="file_upload" class="file-upload__input">
                                    <?php
                                        if(!empty($post['postAttachment'])) {
                                           echo $post['postAttachment']['file_name']; 
                                        }
                                    ?>
                                </span>
                            </div>
                        </div>
                    </div>
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