@extends('template.default')
<title>DICO - Post</title>
@section('content')
    <div id="page-content" class="post-details create-post">
        <div id='wrap'>
            <div id="page-heading">
                <ol class="breadcrumb">
                    <li><a href="{{ url('/home') }}">Dashboard</a></li>
                    <li><a href="{{ route('post.index') }}">Post</a></li>
                    <li class="active">Create Post</li>
                </ol>
                <h1 class="tp-bp-0">Create Post</h1>
                <hr class="border-out-hr">
            </div>
            <div class="container">
                <div class="row">
                    <form name="post_form" id="post_form" method="post" class="common-form" action="{{route('post.store')}}" enctype="multipart/form-data">
                        <div class="col-sm-8" id="post-detail-left">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label class="text-15">Post<span>*</span></label>
                                <div class="check-wrap box-check">
                                    <label class="check idea-check">Idea    
                                       
                                    </label> <input type="checkbox" name="post_type" id="post_type_idea" value="idea" class="post_type check idea-check">
                                        <span class="checked"></span>
                                    <label class="check question-check">Question    
                                        
                                    </label><input type="checkbox" name="post_type" id="post_type_question" value="question" class="post_type">
                                        <span class="checked"></span>
                                    <label class="check challenges-check">Challenge    
                                        
                                    </label><input type="checkbox" name="post_type" id="post_type_challenge" value="challenge" class="post_type">
                                        <span class="checked"></span>
                                </div>
                                <div id="err_post_type"></div>
                            </div>
                            <div class="form-group m-b-20">
                                    <label class="text-15">Post Title<span>*</span></label>
                                    <input type="text" name="post_title" id="post_title" placeholder="Post Title" maxlength="{{POST_TITLE_LIMIT}}" class="form-control required">
                            </div>
                            <div class="form-group">
                                    <label class="text-15">Post Description</label>
                                    <textarea name="post_description" id="post_description" placeholder="Post Description" class="form-control"></textarea>
                            </div>
                            <?php /*<div class="form-group">
                                <div class="col-xs-12 form-group">
                                <span class="btn btn-primary fileinput-button">
                                    <i class="fa fa-upload"></i>
                                    <span>upload</span>
                                    <input type="file" name="file_upload" id="file_upload" class="file-upload__input">
                                </span>
                                </div>
                            </div>*/?>
                            <div class="form-group">
                                    <label>Tags</label>
                                    <input type="hidden" name="post_tags" id="mySingleField" value="">
                                    <ul id="singleFieldTags"></ul>
                            </div>  
                            <?php
                            if(isset($company) && $company->allow_anonymous == 1) {
                            ?>
                            <div class="btn-wrap-div">
                                <label class="check">Post as Anonymous<input type="checkbox" name="is_anonymous" id="is_anonymous">
                                    <span class="checkmark"></span>
                                </label> 
                                <a href="{{ route('post.index') }}" class="st-btn btn-default">Back</a>
                                <input type="submit" name="save" id="save" value="Submit" class="st-btn">
                                <div class="upload-btn-wrapper">
                                    <button class="upload-btn">Upload Files</button>
                                    <input type="file" name="file_upload" id="file_upload" class="file-upload__input">
                                </div>
                            </div>
                            <?php } ?>
                            
                            <?php /*<div class="form-group">
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
                            </div><?php */?>
                            <?php /*<div class="panel-footer">
                                <div class="row col-xs-12">
                                <div class="btn-toolbar">
                                    <a href="{{ route('post.index') }}" class="btn btn-default">Back</a>
                                    <input type="submit" name="save" id="save" value="Submit" class="btn btn-primary">
                                </div>
                                <div style="clear: both;"></div>
                            </div>
                            </div>*/?>
                        </div>
                        <div class="col-sm-4" id="post-detail-right">
                        <div class="category">
                                     <div class="main-group-wrap">
                                        <div class="category-tab tp-bp-0"> 
                                            <label class="check">Groups<input type="checkbox" name="user_groups_all" id="checkAll">
                                              <span class="checkmark"></span>
                                          </label>
                                        </div>
                                        <?php
                                            if(!empty($groups)) {
                                               foreach($groups as $group) { 
                                        ?>
                                        <div class="category-detials">
                                            <label class="check text-12">{{$group->group_name}}
                                                <input type="checkbox" name="user_groups[]" id="user_groups_{{$group->id}}" value="{{$group->id}}">
                                                <span class="checkmark"></span>
                                             </label>
                                        </div> 
                                        <?php } } else { ?>
                                         <div class="category-detials">No group found.</div>
                                        <?php } ?>
                                    </div>
                                </div>
                        </div>
                    </form>    
                </div>
            </div>
        </div>
    </div>
@stop
@push('javascripts')
<script type="text/javascript">
       // $(function(){
            var sampleTags = [];
           // $(document).ready(function() {
                //var _token = CSRF_TOKEN;
                $.ajax({
                    url: SITE_URL + '/tags',
                    type: 'GET',
                    //data: {_token},
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
            //});
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
        $("#checkAll").click(function () {
            $("input[name*='user_groups[]']").not(this).prop('checked', this.checked);
        });
    </script>
    @endpush
