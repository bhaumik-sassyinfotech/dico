@extends('template.default')
<title>DICO - Post</title>
@section('content')


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
                    <div class="panel-body">
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
                                <label>Tags</label><br/>
                                <?php 
                                    $tags = "";
                                    if(!empty($post['postTag'])) {
                                        $i = 0;
                                        foreach($post['postTag'] as $postTags) {
                                            if($i > 0) {
                                                $tags .= ",";
                                            }
                                            $tags .= $postTags['tag']['tag_name'];
                                            $i++;
                                        }
                                    }
                                 ?>   
                                <input type="hidden" name="post_tags" id="mySingleField" value="{{$tags}}">
                                <ul id="singleFieldTags"></ul>
                            </div>
                        </div> 
                        <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label class="control-label" for="user_groups">Group:</label>
                                    <select name="user_groups[]" id="user_groups" class="form-control" multiple="multiple">
                                        <?php
                                           // dd($post);
                                            if(!empty($post->group_id)) { 
                                                $post_group = explode(",", $post->group_id);
                                            }
                                            else {
                                                $post_group = array();
                                            }
                                           // dd($post_group);
                                            if(!empty($groups)) {
                                                foreach($groups as $group) {
                                                ?>    
                                        <option value="{{$group->id}}" <?php if(in_array($group->id, $post_group)) { echo "selected"; } ?>>{{$group->group_name}}</option>
                                                <?php    
                                                }
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                    </div>
                    <div class="panel-footer">
                        <div class="row col-xs-12">
                        <div class="btn-toolbar">
                            <a href="{{ route('post.index') }}" class="btn btn-default">Back</a>
                            <input type="submit" name="save" id="save" value="Submit" class="btn btn-primary">
                        </div>
                        <div style="clear: both;"></div>
                    </div>
                    </div>
               {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@stop
@push('javascripts')
<script>
        $(function(){
            var sampleTags = [];
            $(document).ready(function() {
                $.ajax({
                    url: SITE_URL + '/tags',
                    type: 'GET',
                    success: function(response) {
                        var res = JSON.parse(response);
                        if(res.status == 1) {
                            var data = res.data;
                            $.each(data, function( index, value ) {
                                sampleTags.push(value.tag_name);
                            });
                        }
                        else {
                            sampleTags = [];
                        }
                    }
                });
            });
            //var sampleTags = ['c++', 'java', 'php', 'coldfusion', 'javascript', 'asp', 'ruby', 'python', 'c', 'scala', 'groovy', 'haskell', 'perl', 'erlang', 'apl', 'cobol', 'go', 'lua'];

            //-------------------------------
            // Minimal
            //-------------------------------
            $('#myTags').tagit();

            //-------------------------------
            // Single field
            //-------------------------------
            $('#singleFieldTags').tagit({
                availableTags: sampleTags,
                // This will make Tag-it submit a single form value, as a comma-delimited field.
                singleField: true,
                singleFieldNode: $('#mySingleField')
            });

            // singleFieldTags2 is an INPUT element, rather than a UL as in the other 
            // examples, so it automatically defaults to singleField.
            $('#singleFieldTags2').tagit({
                availableTags: sampleTags
            });

            //-------------------------------
            // Preloading data in markup
            //-------------------------------
            $('#myULTags').tagit({
                availableTags: sampleTags, // this param is of course optional. it's for autocomplete.
                // configure the name of the input field (will be submitted with form), default: item[tags]
                itemName: 'item',
                fieldName: 'tags'
            });

            //-------------------------------
            // Tag events
            //-------------------------------
            var eventTags = $('#eventTags');

            var addEvent = function(text) {
                $('#events_container').append(text + '<br>');
            };

            eventTags.tagit({
                availableTags: sampleTags,
                beforeTagAdded: function(evt, ui) {
                    if (!ui.duringInitialization) {
                        addEvent('beforeTagAdded: ' + eventTags.tagit('tagLabel', ui.tag));
                    }
                },
                afterTagAdded: function(evt, ui) {
                    if (!ui.duringInitialization) {
                        addEvent('afterTagAdded: ' + eventTags.tagit('tagLabel', ui.tag));
                    }
                },
                beforeTagRemoved: function(evt, ui) {
                    addEvent('beforeTagRemoved: ' + eventTags.tagit('tagLabel', ui.tag));
                },
                afterTagRemoved: function(evt, ui) {
                    addEvent('afterTagRemoved: ' + eventTags.tagit('tagLabel', ui.tag));
                },
                onTagClicked: function(evt, ui) {
                    addEvent('onTagClicked: ' + eventTags.tagit('tagLabel', ui.tag));
                },
                onTagExists: function(evt, ui) {
                    addEvent('onTagExists: ' + eventTags.tagit('tagLabel', ui.existingTag));
                }
            });

            //-------------------------------
            // Read-only
            //-------------------------------
            $('#readOnlyTags').tagit({
                readOnly: true
            });

            //-------------------------------
            // Tag-it methods
            //-------------------------------
            $('#methodTags').tagit({
                availableTags: sampleTags
            });

            //-------------------------------
            // Allow spaces without quotes.
            //-------------------------------
            $('#allowSpacesTags').tagit({
                availableTags: sampleTags,
                allowSpaces: true
            });

            //-------------------------------
            // Remove confirmation
            //-------------------------------
            $('#removeConfirmationTags').tagit({
                availableTags: sampleTags,
                removeConfirmation: true
            });
            
        });
    </script>
    @endpush