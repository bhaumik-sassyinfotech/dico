
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
    {!! Form::open(['method' => 'PUT', 'route' => ['idea.update', $post->id],'enctype'=>'multipart/form-data', 'id' => 'post_form']) !!}
    {{ csrf_field() }}
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
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 form-group">
                                <label class="col-xs-12">Post<span>*</span></label><br>
                                <div class="col-xs-4 form-group"><label for="post_type_idea"><input type="checkbox" class="post_type"
                                                                                                    name="post_type"
                                                                                                    id="post_type_idea"
                                                                                                    value="idea" {{ $post->post_type == 'idea' ? "checked" : ''}}>
                                        Idea</label>
                                </div>
                                <div class="col-xs-4 form-group"><label for="post_type_question"><input type="checkbox" class="post_type"
                                                                                                        name="post_type"
                                                                                                        id="post_type_question"
                                                                                                        value="question" {{ $post->post_type == 'question' ? "checked" : ''}}>
                                        Question</label>
                                </div>
                                <div class="col-xs-4 form-group"><label for="post_type_challenges"><input
                                                type="checkbox" class="post_type" name="post_type"
                                                id="post_type_challenges"
                                                value="challenge" {{ $post->post_type == 'challenge' ? "checked" : ''}}>
                                        Challenges</label>
                                </div>
                                <div class="col-md-12" id="err_post_type"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 form-group">
                                <label>Post Title<span>*</span></label>
                                <input type="text" name="post_title" id="post_title" value="{{$post->post_title}}"
                                       placeholder="Post Title" class="form-control required">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 form-group">
                                <label>Post Description</label>
                                <textarea name="post_description" id="post_description" placeholder="Post Description"
                                          class="form-control">{{nl2br($post->post_description)}}</textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 form-group">
                                <label id="is_anonymous"><input type="checkbox" name="is_anonymous"
                                                                id="is_anonymous" {{  $post->is_anonymous == 1 ? "checked" : '' }}>Anonymous</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 form-group">
                                <span class="btn btn-primary fileinput-button">
                                    <i class="fa fa-upload"></i>
                                    <span>upload</span>
                                    <input type="file" name="file_upload" id="file_upload" class="file-upload__input">
                                    <?php
                                    if ( !empty($post[ 'postAttachment' ]) )
                                    {
                                        echo $post[ 'postAttachment' ][ 'file_name' ];
                                    }
                                    ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="btn-toolbar col-xs-12">
                                <a href="{{ route('post.index') }}" class="btn btn-default">Back</a>
                                <input type="submit" name="save" id="save" class="btn btn-primary">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
@stop