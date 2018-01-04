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
                    <form name="post_form" id="post_form" method="post" action="{{route('post.store')}}"
                          enctype="multipart/form-data">
                        {{ csrf_field() }}
                        
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label>Post<span>*</span></label><br>
                                    <div class="col-xs-4 form-group"><input type="checkbox" name="post_type"
                                                                            id="post_type_idea" value="idea" class="post_type"> Idea
                                    </div>
                                    <div class="col-xs-4 form-group"><input type="checkbox" name="post_type"
                                                                            id="post_type_question" value="question" class="post_type">
                                        Question
                                    </div>
                                    <div class="col-xs-4 form-group"><input type="checkbox" name="post_type"
                                                                            id="post_type_challenges"
                                                                            value="challenge" class="post_type"> Challenge
                                    </div>
                                    <div id="err_post_type"></div>
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
                                <span class="btn btn-primary fileinput-button">
                                    <i class="fa fa-upload"></i>
                                    <span>upload</span>
                                    <input type="file" name="file_upload" id="file_upload" class="file-upload__input">
                                </span>
                                </div>
                            </div>
                            <?php
                            if(isset($company) && $company->allow_anonymous == 1) {
                            ?>
                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label>Is Anonymous</label><br/>
                                    <input type="checkbox" name="is_anonymous" id="is_anonymous">
                                </div>
                            </div>
                            <?php } ?>
                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label>Tags</label><br/>
                                    <input type="hidden" name="post_tags" id="mySingleField" value="">
                                    <ul id="singleFieldTags"></ul>
                                </div>
                            </div>  
                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label class="control-label" for="user_groups">Group:</label>
                                    <select name="user_groups[]" id="user_groups" class="form-control" multiple="multiple">
                                        <?php
                                            if(!empty($groups)) {
                                                foreach($groups as $group) {
                                                ?>    
                                        <option value="{{$group->id}}">{{$group->group_name}}</option>
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
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
@push('javascripts')
<script>
        // $(function(){
            var sampleTags = [];
            // $(document).ready(function() {
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
            // });
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
            
        // });
    </script>
    @endpush
