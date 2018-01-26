@extends('template.default')
<title>DICO - Meetings</title>
@section('content')
    
    
<div id="page-content" class="meeting-details padding-box idea-details">
        <div id='wrap'>
            <div id="page-heading">
                <ol class="breadcrumb">
                    <li><a href="{{ url('/home') }}">Dashboard</a></li>
                    <li><a href="{{ route('meeting.index') }}">Meetings</a></li>
                    <li class="active">View Meeting</li>
                </ol>
                <h1 class="icon-mark tp-bp-0">Meeting Details</h1>
                <hr class="border-out-hr">
            </div>
            <div class="container">
                <div class="row">
                    <div id="post-detail-left" class="col-sm-8">
                        <div class="group-wrap">
                            <div class="pull-left">
                                <h3>{{$meeting->meeting_title}}</h3>
                                <div class="user-wrap"> 
                                    <div class="user-img"> 
                                        @if(empty($meeting->meetingCreator->profile_image))
                                            <img src="{{ asset(DEFAULT_PROFILE_IMAGE) }}">
                                        @else
                                            <img src="{{ asset(PROFILE_PATH.$meeting->meetingCreator->profile_image) }}">
                                        @endif
                                    </div> 
                                    <p class="user-icon">-
                                    <a href="{{url('view_profile', Helpers::encode_url($meeting->meetingCreator->id))}}">{{ $meeting->meetingCreator->name }}</a><span>on {{ date(DATE_FORMAT , strtotime($meeting->created_at)) }}</span></p>
                                </div>
                            </div>
                            <div class="pull-right">
                                <div class="options">
                                    <div class="fmr-10">
                                        <a class="set-alarm" href="">a</a>
                                        @if($meeting->created_by == Auth::user()->id)
                                            <a href="{{ route('meeting.edit' , Helpers::encode_url($meeting->id) ) }}"
                                               class="set-edit">Edit</a>
                                            <a href="{{ route('deleteMeeting' , Helpers::encode_url($meeting->id) ) }}"
                                               class="set-delete">Delete</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                            <div class="post-wrap-details">
                                <p class="text-12">
                                    {{ nl2br($meeting->meeting_description) }}
                                </p>
                                @if($meeting->created_by == Auth::user()->id && $meeting->is_finalized == '0')
                                    <div class="post-btn-wrap">
                                        <div class="post-btn deny">
                                            <a href="javascript:void(0);" data-toggle="modal" data-target="#finalizeMeeting">Finalise Meeting</a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div id="finalizeMeeting" class="modal fade" role="dialog">
                                <div class="modal-dialog modal-lg">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Finalize meeting</h4>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('finalizeMeeting') }}" method="POST" id="finalize_meeting_form">
                                                {{ csrf_field() }}
                                                <input type="hidden" value="{{ $meeting->id }}" name="meeting_id">
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <label for="">Comment</label>
                                                        <textarea name="meeting_comment" id="meeting_comment" cols="30" rows="10"></textarea>
                                                    </div>
                                                    <div class="col-xs-12">
                                                        <label for="">Summary</label>
                                                        <textarea name="meeting_summary" id="meeting_summary" cols="30" rows="10"></textarea>
                                                    </div>
                                                    <div class="col-xs-3">
                                                        <input type="submit" value="Submit" class="st-btn saveBtn">
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
        
                                </div>
                            </div>
                            <hr class="border-in-hr">
                                <form name="post_comment_form" id="post_comment_form" method="post" action="{{url('/meeting/saveComment',$meeting->id)}}" enctype="multipart/form-data" class="post-form">
                                    {{ csrf_field() }}
                                <input type="hidden" name="post_id" id="post_id" value="{{$meeting->id }}">
                                <div class="field-group comment">
                                    <textarea name="comment_text" id="comment_text" placeholder="Leave a comment here"></textarea>
                                </div>
                                <div class="field-group checkbox-btn">
                                    <div class="pull-left">
                                        <input value="Submit" name="submit" id="submit" class="st-btn" type="submit">
                                    </div>
                                </div>
                            </form>
                            
                            <hr class="border-in-hr">
                            <div class="container">
                                <form name="commentbox_form" id="commentbox_form" class="form-horizontal row-border  profile-page">
                                <?php foreach($meeting->meetingComment as $comment) { ?>
                                <div class="row" id="commentreply_{{$comment['id']}}">
                                    <div class="col-sm-2 user-image">
                                        <div class="img-wrap">
                                            <?php
                                                $profile_img = '';
                                                if(empty($comment->commentUser->profile_image))
                                                    $profile_img = DEFAULT_PROFILE_IMAGE;
                                                else
                                                    $profile_img = PROFILE_PATH.$comment->commentUser->profile_image;
                                            ?>
                                            <img alt="post user" src="{{ asset($profile_img) }}">
                                        </div>
                                    <?php
                                        $commentUser = $comment->commentUser;
                                        $comment_id = Helpers::encode_url($commentUser->id);
                                        
                                        if (!empty($commentUser->following && count($commentUser->following) > 0 && $commentUser->id != Auth::user()->id))
                                        {
                                            if ($commentUser['following'][0]->status == 1)
                                            { ?>
                                                <a href="{{ url('/view_profile/'.$comment_id) }}">Unfollow</a>
                                                <?php
                                            } else
                                            { ?>
                                                <a href="{{ url('/view_profile/'.$comment_id) }}"
                                                >Follow</a>
                                              <?php
                                            }
                                        } else if ($commentUser->id != Auth::user()->id)
                                        { ?>
                                                <a href="{{ url('/view_profile/'.$comment_id) }}"
                                                >Follow</a>
                                            <?php
                                        }
                                        ?>

                                    
                                    </div>
                                    <div class="col-sm-10 user-rply">
                                        <div class="post-inner-reply">
                                            <div class="pull-left post-user-nam">
                                                <a href="{{url('view_profile', Helpers::encode_url($meeting->meetingCreator->id))}}">{{ $comment->commentUser->name }}</a>
                                                <p>- on {{ date(DATE_FORMAT,strtotime($comment->created_at)) }}</p>
                                            </div>
                                            <div class="pull-right post-reply-pop">
                                                <div class="options">
                                                    <div class="fmr-10">
                                                          @if($comment->commentUser->id == Auth::user()->id)
                                                            <a class="set-edit" href="javascript:void(0)" onclick="editComment(<?=$comment['id']?>);">e</a>
                                                            <a class="set-alarm" href="{{ url('meeting/deleteMeetingComment/'.$comment->id) }}">a</a>
                                                          @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="profanity" id="comment_disp_<?=$comment['id']?>"><?php echo $comment->comment_reply; ?></p>
                                            <textarea name="comment_text" id="comment_text_<?=$comment['id']?>" readonly="" class="text-12 textarea-width" style="display: none;"><?php echo  $comment->comment_reply; ?></textarea>
                                            <div class="btn-wrap-div">
                                                <input type="button" name="update_comment" id="update_comment_<?=$comment['id']?>" value="Save" class="st-btn" onclick="updateComment(<?=$comment['id']?>,<?=$comment['id']?>)" style="display: none;"/>
                                                <input type="button" name="cancel_comment" id="cancel_comment_<?=$comment['id']?>" value="Cancel" class="btn btn-secondary btn-st" onClick="this.form.reset();closeComment(<?=$comment['id']?>)" style="display: none;"/>
                                            </div>    
                                        <div class="rply-box">
                                            <div class="rply-count like">
                                                <a href="javascript:void(0)" id="like_comment_{{$comment['id']}}" onclick="likeAttactmentComment({{$comment['id']}},{{$comment['id']}});">
                                                <?php
                                                    if (!empty($comment['commentUserLike'])) {
                                                ?>
                                                    <i class="fa fa-thumbs-up"></i>
                                                <?php } else {?>
                                                    <i class="fa fa-thumbs-o-up"></i>
                                                <?php }?>
                                                </a>
                                                <span id="comment_like_count_{{$comment['id']}}"><?php echo count($comment['commentLike']); ?></span>
                                            </div>
                                            <div class="rply-count dislike">
                                                <a href="javascript:void(0)" id="dislike_comment_{{$comment['id']}}" onclick="dislikeAttachmentComment({{$comment['id']}},{{$comment['id']}});">
                                                <?php
                                                    if (!empty($comment['commentUserDisLike'])) {
                                                ?>
                                                    <i class="fa fa-thumbs-down"></i>
                                                <?php } else {?>
                                                    <i class="fa fa-thumbs-o-down"></i>
                                                <?php }?>
                                                </a>
                                                <span id="comment_dislike_count_{{$comment['id']}}"><?php echo count($comment['commentDisLike']); ?></span>
                                            </div>
                                            <div class="rply-count">
                                            <a href="javascript:void(0);" onclick="openCommentReplyBox({{$comment['id']}})" id="modalComment"><i class="fa fa-reply" aria-hidden="true"></i></a>
                                            <span><?php echo count($comment['commentReply']); ?></span>
                                            </div>    
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                                </form>
                                <!-- Start comment reply popupbox -->
                                <div id="myModalComment" class="modal fade" role="dialog">
                                <div class="modal-dialog">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <div id="commentReplyList"></div> 
                                    <div class="modal-header">
                                        <h4 class="modal-title">Comment Here</h4>
                                    </div>
                                    <form name="reply_form" id="reply_form" class="form-horizontal row-border  profile-page">
                                        <div class="modal-body">
                                            <input type="hidden" id="commentId" name="commentId">
                                            <div class="row">
                                                <textarea name="comment_reply_text" id="comment_reply_text" class="form-control autosize" placeholder="Leave a comment here"></textarea>
                                            </div>
                                            <div class="row">
                                                <label class="checkbox-inline"><input type="checkbox" name="is_anonymous" id="is_anonymous">Anonymous</label><br>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-primary" onclick="comment_reply()">Submit</button>
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                    </form>
                                    </div>
                                </div></div>
                        <!-- End comment reply popupbox -->
                            </div>
                        
                        <!--</div>-->
                    </div>
                    <div id="post-detail-right" class="col-sm-4">
                        <div class="category">
                            <h2>Members<span>({{ count($meeting_users) }} Invited)</span></h2>
                            <div class="post-category">
                                @foreach($meeting_users as $user)
                                    <div class="member-wrap">
                                        <div class="member-img">
                                            <?php
                                            //$path = 'assets/img/member1.PNG';
                                            $admin_icon = asset('assets/img/member-icon.PNG');
                                            $profile_picture = (!empty($user->profile_image)) ? PROFILE_PATH. $user->profile_image : DEFAULT_PROFILE_IMAGE; ?>
                                            <img src="{{ asset($profile_picture) }}" alt="no">
                                            @if($user->is_admin == '1')
                                                <figure><img src="{{ $admin_icon }}" alt="member-icon"></figure>
                                            @endif
                                        </div>
                                        <div class="member-details">
                                            <h3 class="text-12"><a href="{{url('view_profile', Helpers::encode_url($meeting->meetingCreator->id))}}">{{ $user->name }}</a></h3>
                                            <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="category">
                            <h2>Uploaded Files</h2>
                            <div class="wrap-name-upload">
                                <div class="select">
                                    <select id="slct" name="slct">
                                            <option>Name</option>
                                            <option>Admin</option>
                                            <option value="Super User">Super User</option>
                                            <option value="Employee">Employee</option>
                                    </select>
                                </div>
                                <div class="upload-btn-wrapper">
                                    <form name="uploadfile" id="uploadfile" method="post" enctype="multipart/form-data">
                                        <input type="hidden" name="meetingId" id="meetingId" value="{{$meeting['id']}}">
                                        <button class="btn" id="uploadBtn">Upload File</button>
                                        <input name="file_upload" id="file_upload" type="file" onchange="uploadFileMeeting();">
                                    </form>
                                </div>
                            </div>
                            <div class="idea-grp post-category" id="meetingAttachment">
                        <?php
                            if(!empty($meeting->meetingAttachment) && count($meeting->meetingAttachment) > 0) {
                            foreach($meeting->meetingAttachment as $attachment) {
                        ?>
                        <div class="member-wrap files-upload">

                            <div class="member-img">
                                <img src="{{asset(DEFAULT_ATTACHMENT_IMAGE)}}" alt="no">
                            </div>
                            <div class="member-details">
                                <h3 class="text-10">{{$attachment->file_name}}</h3>
                                <p>Uploaded By:<a href="#">{{$attachment->attachmentUser->name}}</a></p>
                            </div>
                        </div>    
                            <?php } }
                                else {
                                    echo "<p class='text-12'>No files uploaded.</p>";
                                }
                            ?>
                    </div> 
                        </div>    
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Comment Reply Modal START-->
    <div id="commentReply" class="modal fade"
         role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close"
                            data-dismiss="modal">&times;
                    </button>
                    <h4 class="modal-title">Comment Here</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <textarea name="comment_text" id="comment_text" class="form-control autosize"
                                  placeholder="Leave a comment here"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-danger"
                            data-dismiss="modal">Close
                    </button>
                    <button type="button" id="reply_to_comment"
                            class="btn btn-success reply_to_comment"
                            data-dismiss="modal">
                        Submit
                    </button>
                
                </div>
            </div>
        </div>
    </div>
    <!-- Comment Reply Modal END-->
@stop

@push('javascripts')
    <script type="text/javascript">
        $('#commentReply').on('show.bs.modal', function (event) {
            // Button that triggered the modal
            var button = $(event.relatedTarget);
            // Extract info from data-* attributes
            var comment_id = button.data('comment-id');
            console.log("Comment ID: " + comment_id);

            var modal = $(this);
            // modal.find('.modal-title').text('New message to ' + recipient);
            // modal.find('.modal-body input').val(recipient);
            $('.reply_to_comment').unbind().click(function (e) {
                // console.log("Clicked 1"+Math.random()); 
                e.preventDefault();
                // console.log("Clicked 1");
                // $(this).data('bs.modal', null);
                replyToComment();
            });
        });

        function replyToComment() {
            $.ajax({
                type: "POST",
                url: SITE_URL + '/',
                data: {},
                success: function (response) {
                    console.log(response);
                }
            });
        }

        $("#post_comment_form").validate({
            rules: {
                'comment_text': {
                    required: true,

                }
            },
            submitHandler: function (form) {
                form.submit();
            }
        });
        function openCommentReplyBox(commentid) {
            if(commentid != "") {
                var _token = CSRF_TOKEN;
                formData = {comment_id:commentid,_token};
                $("#spinner").show();
                $.ajax({
                    url: SITE_URL + '/getCommentReply',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        $("#spinner").hide();
                        var res = JSON.parse(response);
                        if(res.status == 1) {
                            $('#commentReplyList').html(res.html);
                            runProfanity();
                        } else {
                            ajaxResponse('error',res.msg);
                        }
                    },
                    error: function(e) {
                        swal("Error", e, "error");
                    }
                });
                $('#reply_form')[0].reset();
                $('.error').html('');
                $('#myModalComment').modal('show');
                $('#commentId').val(commentid);
            }else {
                swal("Error", "comment not found", "error");
            }
        }
        function editComment(id) {
            $('#comment_text_' + id).removeProp('readonly').slideDown('fast');
            $('#update_comment_' + id).css('display', 'inline-block');
            $('#comment_text_'+id).css('background-color','white');
            $("#comment_disp_"+id).slideUp('fast');
            $('#cancel_comment_'+id).css('display','inline-block');
        }
        function closeComment(id) {
            $('#comment_text_'+id).removeProp('readonly').slideUp('fast');
            $('#update_comment_' + id).css('display', 'none');
            $('#comment_text_'+id).css('background-color','transparent');
            $("#comment_disp_"+id).slideDown('fast');
            $('#cancel_comment_'+id).css('display','none');
        }
        function updateComment(id,elementid) {
        if($('#commentbox_form').valid() == 1) {
            var comment = $('#comment_text_'+elementid).val();
            var _token = CSRF_TOKEN;
            formData = {id:id,comment:comment,_token};
            $("#spinner").show();
            $.ajax({
                url: SITE_URL + '/meeting_comment_update',
                type: 'POST',
                data: formData,
                success: function(response) {
                    $("#spinner").hide();
                    res = JSON.parse(response);
                    if (res.status == 1) {
                        //swal("Success", res.msg, "success");
                        $('#comment_text_'+elementid).attr('readonly',true);
                        $('#comment_text_'+elementid).css('background-color','transparent');
                        $('#update_comment_'+elementid).css('display','none');
                        $('#cancel_comment_'+elementid).css('display','none');
                        ajaxResponse('success',res.msg);
                        location.reload();
                    } else {
                        ajaxResponse('error',res.msg);
                    }
                },
                error: function(e) {
                    swal("Error", e, "error");
                }
            });
        }
    }
    </script>
@endpush
