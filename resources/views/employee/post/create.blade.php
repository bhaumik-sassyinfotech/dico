
@extends('template.default')
<title>DICO - Post</title>
@section('content')
    
    <div id="page-content" class="post-details create-post">
        <div id='wrap'>
            @include('template.notification')
            <div id="page-heading">
                <ol class="breadcrumb">
                    <li><a href="{{ url('/home') }}">Dashboard</a></li>
                    <li><a href="{{ route('post.index') }}">Post</a></li>
                    <li class="active">Create Post</li>
                </ol>
                <h1 class="tp-bp-0">Create Post</h1>
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
                    <form action="{{ route('post.store') }}" enctype="multipart/form-data" method="POST" id="post_form" class="common-form">
                    {{ csrf_field() }}
                    <div class="col-sm-8" id="post-detail-left">
                        <div class="form-group">
                            <label class="text-15">Post<span>*</span></label>
                            <div class="check-wrap box-check">
                                <label class="check idea-check">Idea
                                    <input type="checkbox" class="post_type" name="post_type" id="post_type_idea"  value="idea" >
                                    <span class="checked"></span>
                                </label>
                                <label class="check question-check">Question
                                    <input type="checkbox" class="post_type" name="post_type" id="post_type_question"  value="question" >
                                    <span class="checked"></span>
                                </label>
                                <label class="check challenges-check">Challenge
                                    <input type="checkbox" class="post_type" name="post_type" id="post_type_challenges"  value="challenge" >
                                    <span class="checked"></span>
                                </label>
                            </div>
                            <div class="col-md-12" id="err_post_type"></div>
                        </div>
                        <div class="form-group">
                            <label>Post Title<span>*</span></label>
                            <input type="text" name="post_title" id="post_title" value="" placeholder="Post Title" class="form-control required">
                        </div>
                        <div class="form-group">
                            <label>Post Description</label>
                            <textarea name="post_description" id="post_description" placeholder="Post Description" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Tags</label><br/>
                            <input  type="hidden" name="post_tags" id="mySingleField" value="">
                            <ul style="width: 71%;" id="singleFieldTags"></ul>
                        </div>
                        
                        
                        <div class="form-group">
                            <label class="control-label" for="user_groups">Group:</label>
                            <select name="user_groups[]" id="user_groups" class="form-control select" multiple="multiple">
                            @foreach($groups as $group)
                                <option value="{{$group->id}}">{{$group->group_name}}</option>
                            @endforeach
                            </select>
                        
                        </div>
                        
                        <div class="btn-wrap-div">
                            @if(isset($company) && $company->allow_anonymous == 1)
                                <label class="check">Post as Anonymous
                                    <input type="checkbox" name="is_anonymous" id="is_anonymous">
                                    <span class="checkmark"></span>
                                </label>
                            @endif
                            <a href="{{ route('post.index') }}" class="st-btn">Back</a>
                            <input type="submit" name="save" id="save" class="st-btn">
                            <div class="upload-btn-wrapper">
                                <button class="upload-btn">Upload Files</button>
                                <input type="file" name="file_upload" id="file_upload" class="file-upload__input">
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
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