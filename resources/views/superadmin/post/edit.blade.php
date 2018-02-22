@extends('template.default')
<title>@lang("label.DICOPost")</title>
@section('content')
<div id="page-content" class="post-details create-post">
    <div id='wrap'>
        <div id="page-heading">
            <ol class="breadcrumb">
                <li><a href="{{ route('/home') }}">@lang("label.adDashboard")</a></li>
                <li><a href="{{ route('post.index') }}">@lang("label.adPost")</a></li>
                <li class="active">@lang("label.EditPost")</li>
            </ol>
            <h1 class="tp-bp-0">@lang("label.EditPost")</h1>
            <hr class="border-out-hr">
        </div>
        <div class="container">
            <div class="row">
                {!! Form::model($post, ['method' => 'PUT', 'route' => ['post.update', $post->id],'enctype'=>'multipart/form-data', 'id' => 'post_form', 'class'=>'common-form']) !!}
                    <div class="col-sm-8" id="post-detail-left">
                        <div class="form-group">
                            <label class="text-15">@lang("label.adPost")<span>*</span></label>
                            <div class="check-wrap box-check">
                                <div class="check <?php if($post->post_type == 'idea') { echo "idea-check";}?>">@lang("label.Idea")    
                                    <input type="checkbox" name="post_type" class="post_type check idea-check" id="post_type_idea" onclick="return false;" value="idea" <?php if($post->post_type == 'idea') { echo "checked"; } ?>>
                                     <label for="post_type_idea"> 
                                         <span class="checked idea-checked"></span>
                                      </label>    
                                </div>
                                <div class="check <?php if($post->post_type == 'question') { echo "question-check";}?>">@lang("label.Question")    
                                    <input type="checkbox" name="post_type" class="post_type check question-check" id="post_type_question" onclick="return false;" value="question" <?php if($post->post_type == 'question') { echo "checked"; } ?>>
                                     <label for="post_type_question"> 
                                         <span class="checked question-checked"></span>
                                      </label>    
                                </div>
                                <div class="check <?php if($post->post_type == 'challenge') { echo "challenges-check";}?>">@lang("label.Challenge")    
                                    <input type="checkbox" name="post_type" class="post_type check challenge-check" id="post_type_challenge" onclick="return false;" value="challenge" <?php if($post->post_type == 'challenge') { echo "checked"; } ?>>
                                     <label for="post_type_challenge"> 
                                         <span class="checked challenges-checked"></span>
                                      </label>    
                                </div>
                            </div>
                            <div id="err_post_type"></div>
                        </div>
                        <div class="form-group">
                            <label>@lang("label.PostTitle")<span>*</span></label>
                            <input type="text" name="post_title" id="post_title" value="{{$post->post_title}}" placeholder="@lang('label.PostTitle')" maxlength="{{POST_TITLE_LIMIT}}" class="form-control required">
                        </div>
                        <div class="form-group">
                            <label>@lang("label.PostDescription")</label>
                            <textarea name="post_description" id="post_description" placeholder="@lang('label.PostDescription')" class="form-control">{{$post->post_description}}</textarea>
                        </div>
                        <?php /* ?><div class="form-group">
                            <span class="btn btn-primary fileinput-button">
                                <i class="fa fa-upload"></i>
                                <span>upload</span>
                                <input type="file" name="file_upload" id="file_upload" class="file-upload__input">
                                <?php
                                    if(!empty($post['postAttachment']) && count($post['postAttachment']) > 0) {
                                       echo $post['postAttachment']['file_name']; 
                                    }
                                ?>
                            </span>
                        </div><?php */ ?>
                        <div class="form-group">
                            <label>Tags</label>
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
                        <div class="btn-wrap-div">
                        <?php
                            if(isset($company) && $company->allow_anonymous == 1) {
                        ?>
                        
                            <label class="check">@lang("label.PostasAnonymous")<input type="checkbox" name="is_anonymous" id="is_anonymous" <?php if($post->is_anonymous == 1) { echo "checked"; } ?>>
                            <span class="checkmark"></span></label>
                        <?php } ?>    
                            <a href="{{ route('post.index') }}" class="st-btn">@lang("label.adBack")</a>
                            <input type="submit" name="save" id="save" value="@lang('label.Submit')" class="st-btn">
                            <?php /*<div class="upload-btn-wrapper">
                                <button class="upload-btn fileinput-button">Upload Files</button>
                                <input type="file" name="file_upload" id="file_upload" class="file-upload__input">
                                <?php
                                    if(!empty($post['postAttachment']) && count($post['postAttachment']) > 0) {
                                       //echo $post['postAttachment']['file_name']; 
                                    }
                                ?>
                            </div> */?>       
                        </div>
                            
                        <?php /* <div class="row">
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
                                    if(!empty($post->group_id)) { 
                                        $post_group = explode(",", $post->group_id);
                                    }
                                    else {
                                        $post_group = array();
                                    }
                                    if(!empty($groups) && count($groups) > 0) {
                                       foreach($groups as $group) { 
                                ?>
                                <div class="category-detials">
                                    <label class="check text-12">{{$group->group_name}}
                                        <input type="checkbox" name="user_groups[]" id="user_groups_{{$group->id}}" value="{{$group->id}}" <?php if(in_array($group->id, $post_group)) { echo "checked"; } ?>>
                                        <span class="checkmark"></span>
                                     </label>
                                </div> 
                                <?php } } else { ?>
                                 <div class="category-detials">@lang("label.NoGroupfound")</div>
                                <?php } ?>
                            </div>
                        </div>
                        
                        <?php
                           // if(!empty($post['postAttachment']) && count($post['postAttachment']) > 0) {
                               //echo $post['postAttachment']['file_name']; 
                        ?>
                        <div class="category">
                            <h2>@lang("label.UploadedFiles")Files</h2>
                            <div class="wrap-name-upload">
                                <div class="select">
                                    <select id="slct" name="slct">
                                            <option>@lang("label.adName")</option>
                                            <option>@lang("label.Admin")</option>
                                            <option value="Super User">@lang("label.SuperUser")</option>
                                            <option value="Employee">@lang("label.Employee")</option>
                                    </select>
                                </div>
                                <div class="upload-btn-wrapper">
                                    <form name="uploadfile" id="uploadfile" method="post" enctype="multipart/form-data">
                                        <input type="hidden" name="postId" id="postId" value="{{$post['id']}}">
                                        <button class="btn" id="uploadBtn">@lang("label.UploadFile")</button>
                                        <input name="file_upload" id="file_upload" type="file" onchange="uploadFile();">
                                    </form>
                                </div>
                            </div>
                            <div class="idea-grp post-category" id="postAttachment">
                                <?php
                                    //dd($post->postAttachment);
                                    if(!empty($post['postAttachment']) && count($post['postAttachment']) > 0) {
                                    foreach($post['postAttachment'] as $attachment) {
                                ?>
                                <div class="member-wrap files-upload">

                                    <div class="member-img">
                                        <img src="{{asset(DEFAULT_ATTACHMENT_IMAGE)}}" alt="no">
                                    </div>
                                    <div class="member-details">
                                        <h3 class="text-10">{{$attachment['file_name']}}</h3>
                                        <p>@lang("label.UploadedBy"):<a href="#">{{$attachment['attachmentUser']['name']}}</a></p>
                                    </div>
                                </div>    
                                    <?php } }
                                        else {
                                            echo "<p class='text-12'>".__('label.Nofilesuploaded')."</p>";
                                        }
                                    ?>
                            </div> 
                        </div>
                        <?php //} ?>
                    </div>
               {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@stop
@push('javascripts')
<script>
       // $(function(){
            var sampleTags = [];
            //$(document).ready(function() {
                $.ajax({
                    url: SITE_URL +'/'+LANG + '/tags',
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
            
            $("#checkAll").click(function () {
                $("input[name*='user_groups[]']").not(this).prop('checked', this.checked);
            });
            var checkboxes = $( "input[name*='user_groups[]']" );
            if ( checkboxes.filter( ':checked' ).length == checkboxes.length && checkboxes.filter( ':checked' ).length > 0)
            {
                $("#checkAll").prop( 'checked', true );
            } else {
                $("#checkAll").removeProp( 'checked' );
            }
       // });
    </script>
    @endpush