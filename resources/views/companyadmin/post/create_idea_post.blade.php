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
    <form name="post_form" id="post_form" method="post" action="{{route('idea.store')}}">
        <div id="page-content">
            <div id='wrap'>
                <div id="page-heading">
                    <ol class="breadcrumb">
                        <li><a href="{{ url('/home') }}">Dashboard</a></li>
                        <li><a href="{{ route('post.index') }}">Post</a></li>
                        <li class="active">Create Post</li>
                    </ol>
                    <h1>Post</h1>
                    <div class="options">
                        <div class="btn-toolbar">
                            <a href="{{ route('post.index') }}" class="btn btn-default">Bac1k</a>
                            <input type="submit" name="save" id="save" class="btn btn-primary">
                        </div>

                    </div>
                </div>
                <div class="container">
                    <div class="panel panel-default">
                        {{ csrf_field() }}

                        <div class="panel-body">
                            <div class="row">
                                <div class="col-xs-12 form-group row ">
                                    <label class="col-xs-12">Post<span>*</span></label><br>
                                    <div class="col-xs-2 form-group"><label for="post_type_idea"><input type="checkbox"
                                                                                                        name="post_type"
                                                                                                        class="post_type"
                                                                                                        id="post_type_idea"
                                                                                                        value="idea">
                                            Idea</label></div>
                                    <div class="col-xs-2 form-group"><label for="post_type_question"><input
                                                    type="checkbox" name="post_type" class="post_type"
                                                    id="post_type_question"
                                                    value="question"> Question</label></div>
                                    <div class="col-xs-2 form-group"><label for="post_type_challenges"><input
                                                    type="checkbox" name="post_type" class="post_type"
                                                    id="post_type_challenges"
                                                    value="challenge"> Challenges</label></div>
                                    <div class="col-md-12" id="err_post_type"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label>Post Title<span>*</span></label>
                                    <input type="text" name="post_title" id="post_title" placeholder="Post Title"
                                           class="form-control required">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label>Post Description</label>
                                    <textarea name="post_description" id="post_description"
                                              placeholder="Post Description" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label id="is_anonymous"><input type="checkbox" name="is_anonymous"
                                                                    id="is_anonymous">Anonymous</label><br/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@stop