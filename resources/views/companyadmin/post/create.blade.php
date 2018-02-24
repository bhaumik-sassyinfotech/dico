@extends('template.default')
<title>@lang("label.DICOPost")</title>
@section('content')
    <div id="page-content" class="post-details create-post">
        <div id='wrap'>
            <div id="page-heading">
                <ol class="breadcrumb">
                    <li><a href="{{ route('/home') }}">@lang("label.adDashboard")</a></li>
                    <li><a href="{{ route('post.index') }}">@lang("label.adPost")</a></li>
                    <li class="active">@lang("label.CreatePost")</li>
                </ol>
                <h1 class="tp-bp-0">@lang("label.CreatePost")</h1>
                <hr class="border-out-hr">
            </div>
            <div class="container">
                <div class="row">
                    <form name="post_form" id="post_form" method="post" class="common-form" action="{{route('post.store')}}" enctype="multipart/form-data">
                        <div class="col-sm-8" id="post-detail-left">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label class="text-15">@lang("label.adPost")<span>*</span></label>
                                <div class="check-wrap box-check">
                                    <?php /*<label class="check idea-check">Idea    
                                       <input type="checkbox" name="post_type" id="post_type_idea" value="idea" class="post_type check idea-check">
                                       <span class="checked"></span>
                                    </label> 
                                    <label class="check question-check">Question    
                                        <input type="checkbox" name="post_type" id="post_type_question" value="question" class="post_type">
                                        <span class="checked"></span>
                                    </label>
                                    <label class="check challenges-check">Challenge    
                                        <input type="checkbox" name="post_type" id="post_type_challenge" value="challenge" class="post_type">
                                        <span class="checked"></span>
                                    </label>
                                    <?php /*<div class="custom-cr-btn">
                                    <input id="checkbox1" type="checkbox" name="post_type" value="">
                                       <label for="checkbox1"><span></span></label>
                                   </div>*/?>
                                    <div class="check ">@lang("label.Idea")   
                                        <input type="checkbox" name="post_type" id="post_type_idea" value="idea" class="post_type check idea-check error">
                                         <label for="post_type_idea"> 
                                             <span class="checked idea-checked"></span>
                                          </label>    
                                    </div>
                                    <div class="check ">@lang("label.Question")    
                                        <input type="checkbox" name="post_type" id="post_type_question" value="question" class="post_type check question-check error">
                                         <label for="post_type_question"> 
                                             <span class="checked question-checked"></span>
                                          </label>    
                                    </div>
                                    <div class="check ">@lang("label.Challenge")    
                                        <input type="checkbox" name="post_type" id="post_type_challenge" value="challenge" class="post_type check challenges-check error">
                                         <label for="post_type_challenge"> 
                                             <span class="checked challenges-checked"></span>
                                          </label>    
                                    </div>
                                </div>
                                <div id="err_post_type"></div>
                            </div>
                            <div class="form-group m-b-20">
                                    <label class="text-15">@lang("label.PostTitle")<span>*</span></label>
                                    <input type="text" name="post_title" id="post_title" placeholder="@lang('label.PostTitle')" maxlength="{{POST_TITLE_LIMIT}}" class="form-control required">
                            </div>
                            <div class="form-group">
                                    <label class="text-15">@lang("label.PostDescription")</label>
                                    <textarea name="post_description" id="post_description" placeholder="@lang('label.PostDescription')" class="form-control"></textarea>
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
                                    <label>@lang("label.Tags")</label>
                                    <input type="hidden" name="post_tags" id="mySingleField" value="">
                                    <ul id="singleFieldTags"></ul>
                            </div>  
                            <div class="btn-wrap-div">
                            <?php
                            if(isset($company) && $company->allow_anonymous == 1) {
                            ?>
                            <label class="check">@lang("label.PostAnonymous")<input type="checkbox" name="is_anonymous" id="is_anonymous">
                                <span class="checkmark"></span>
                            </label> 
                            <?php } ?>
                                <a href="{{ route('post.index') }}" class="st-btn btn-default">@lang("label.adBack")</a>
                                <input type="submit" name="save" id="save" value="@lang('label.adSubmit')" class="st-btn">
                                <div class="upload-btn-wrapper">
                                    <button class="upload-btn">@lang("label.adUpload Files")</button>
                                    <input type="file" name="file_upload" id="file_upload" class="file-upload__input">
                                </div>
                            </div>
                            
                            
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
                                       <label class="check">@lang("label.Groups")<input type="checkbox" name="user_groups_all" id="checkAll">
                                         <span class="checkmark"></span>
                                     </label>
                                   </div>
                                   <?php
                                       if(!empty($groups) && count($groups) > 0) {
                                          foreach($groups as $group) { 
                                   ?>
                                   <div class="category-detials">
                                       <label class="check text-12">{{$group->group_name}}
                                           <input type="checkbox" name="user_groups[]" id="user_groups_{{$group->id}}" value="{{$group->id}}">
                                           <span class="checkmark"></span>
                                        </label>
                                   </div> 
                                   <?php } } else { ?>
                                    <div class="category-detials">@lang("label.NoGroupfound")</div>
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
                    url: SITE_URL +'/'+LANG+ '/tags',
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
        /*$('.post_type').click(function() {
            var posttype = $(this).val();
            if(posttype == 'idea') {
                $(this).parent().removeClass('idea-check');
                $(this).parent().addClass('idea-check');
            } else if(posttype == 'question') {
                $(this).parent().removeClass('question-check');
                $(this).parent().addClass('question-check');
            } else if(posttype == 'challenge') {
                $(this).parent().removeClass('challenges-check');
                $(this).parent().addClass('challenges-check');
            } 
        });*/
        
    </script>
    @endpush
