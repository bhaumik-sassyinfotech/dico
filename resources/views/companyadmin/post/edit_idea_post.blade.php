
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
    <div id="page-content" class="post-details create-post">
        <div id='wrap'>
            <div id="page-heading">
                <ol class="breadcrumb">
                    <li><a href="{{ url('/home') }}">Dashboard</a></li>
                    <li><a href="{{ route('post.index') }}">Post</a></li>
                    <li class="active">Edit Post</li>
                </ol>
                <h1 class="tp-bp-0">Edit Post</h1>
                <hr class="border-out-hr">
                <?php /*<div class="options">
                    <div class="btn-toolbar">
                        <a href="{{ route('post.index') }}" class="btn btn-default">Back</a>
                        <input type="submit" name="save" id="save" class="btn btn-primary">
                    </div>
                </div>*/?>
            </div>
            <div class="container">
                <div class="row">
                    {!! Form::open(['method' => 'PUT', 'route' => ['idea.update', $post->id],'enctype'=>'multipart/form-data', 'id' => 'post_form', 'class' => 'common-form']) !!}
                    {{ csrf_field() }}
                    <div class="col-sm-8" id="post-detail-left">
                        <div class="form-group">
                            <label class="text-15">Post<span>*</span></label>
                                <div class="check-wrap box-check">
                                    <label class="check idea-check">Idea
                                        <input type="checkbox" class="post_type" name="post_type" id="post_type_idea" disabled="" value="idea" {{ $post->post_type == 'idea' ? "checked" : ''}}>
                                        <span class="checked"></span>
                                    </label>
                                    <label class="check question-check">Question
                                        <input type="checkbox" class="post_type" name="post_type" id="post_type_question" disabled="" value="question" {{ $post->post_type == 'question' ? "checked" : ''}}>
                                        <span class="checked"></span>
                                    </label>
                                    <label class="check challenges-check">Challenge
                                        <input type="checkbox" class="post_type" name="post_type" id="post_type_challenges" disabled="" value="challenge" {{ $post->post_type == 'challenge' ? "checked" : ''}}>
                                        <span class="checked"></span>
                                    </label>
                                </div>
                            <div class="col-md-12" id="err_post_type"></div>
                        </div>        
                        <div class="form-group">
                            <label>Post Title<span>*</span></label>
                            <input type="text" name="post_title" id="post_title" value="{{$post->post_title}}" placeholder="Post Title" class="form-control required">
                        </div>
                        <div class="form-group">
                            <label>Post Description</label>
                            <textarea name="post_description" id="post_description" placeholder="Post Description" class="form-control">{{nl2br($post->post_description)}}</textarea>
                        </div>
                        <div class="btn-wrap-div">
                            <label class="check">Post as Anonymous
                                <input type="checkbox" name="is_anonymous" id="is_anonymous" {{  $post->is_anonymous == 1 ? "checked" : '' }}>
                                <span class="checkmark"></span>
                            </label>
                            <a href="{{ route('post.index') }}" class="st-btn">Back</a>
                            <input type="submit" name="save" id="save" class="st-btn">
                            <div class="upload-btn-wrapper">
                                <button class="upload-btn">Upload Files</button>
                                <input type="file" name="file_upload" id="file_upload" class="file-upload__input">
                            </div>
                        </div>
                    </div>
                {!! Form::close() !!}            
                </div>
                </div>
            </div>
        </div>
    </div>
    
@stop