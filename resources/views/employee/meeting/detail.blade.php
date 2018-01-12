@extends('template.default')
<title>DICO - Post</title>
@section('content')


    <div id="page-content">
        <div id='wrap'>
            <div id="page-heading">
                <ol class="breadcrumb">
                    <li><a href="{{ url('/home') }}">Dashboard</a></li>
                    <li><a href="{{ route('meeting.index') }}">Meeting</a></li>
                    <li class="active">View Meeting</li>
                </ol>
                <h1 class="icon-mark tp-bp-0">Meeting Details</h1>
                <hr class="border-out-hr">
            </div>
            <div class="container">
                <div class="row">
                    <div id="post-detail-left" class="col-sm-8">
                        @include('template.notification')
                        <div class="group-wrap">
                            <div class="pull-left">
                                <h3 class="profanity">{{$meeting->meeting_title}}</h3>
                                <p class="user-icon">-{{ $meeting->meetingCreator->name }}<span>on {{ date('d-m-Y' , strtotime($meeting->created_at)) }}</span></p>
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
                            <div class="post-wrap-details">
                                <p class="text-12 profanity">
                                    {{ nl2br($meeting->meeting_description) }}
                                </p>

                                    <div class="post-btn-wrap">
                                        <div class="post-btn deny">
                                            @if($meeting->created_by == Auth::user()->id && $meeting->is_finalized == '0')
                                            <a href="javascript:void(0);" data-toggle="modal" data-target="#finalizeMeeting">Finalise Meeting</a>
                                            @endif
                                            @if($meeting->created_by != Auth::user()->id &&  (in_array(Auth::user()->id , $meeting_user_ids)))
                                                <a href="javascript:void(0);" class="leaveMeeting">Leave Meeting</a>
                                            @endif
                                        </div>
                                    </div>

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
                                                <input type="hidden" value="{{ $meeting->id }}" name="meeting_id" id="meeting_id">
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
                            @if(in_array(Auth::user()->id , $meeting_user_ids))
                                <form name="post_comment_form" id="post_comment_form" method="post"
                                          action="{{url('/meeting/saveComment',$meeting->id)}}" enctype="multipart/form-data" class="post-form">
                                        {{ csrf_field() }}
                                    <input type="hidden" name="post_id" id="post_id" value="{{$meeting->id }}">
                                    <div class="field-group comment">
                                        <textarea name="comment_text" placeholder="Leave a comment here"></textarea>
                                    </div>
                                    <div class="btn-wrap-div">
                                        <input type="submit" class="st-btn" value="Submit">
                                        <div class="upload-btn-wrapper">
                                            <button class="upload-btn">Upload Files</button>
                                            <input type="file" name="file_upload" id="file_upload" class="file-upload__input">
                                        </div>
                                    </div>
                                </form>
                                <hr class="border-in-hr">
                            @endif
                            <div class="container">
                                @if(count($meeting->meetingComment) > 0)
                                    @foreach($meeting->meetingComment as $comment)
                                        <div class="row" id="comment_box_{{ $comment->id }}">
                                            <div class="col-sm-2 user-image">
                                                <div class="img-wrap">
                                                    <?php
$profile_img = '';
$profile_pic = $comment->commentUser->profile_image;
if ($profile_pic == "") {
	$profile_img = asset('assets/img/default_user.jpg');
} else {
	$profile_img = asset('public/uploads/profile_pic/' . $profile_pic);
}
?>
                                                    <img alt="post user" src="{{ $profile_img }}">
                                                </div>
                                            <?php
$commentUser = $comment->commentUser;
$comment_id = Helpers::encode_url($commentUser->id);

if (!empty($commentUser->following && count($commentUser->following) > 0 && $commentUser->id != Auth::user()->id)) {
	if ($commentUser['following'][0]->status == 1) {?>
                                                        <a href="{{ url('/view_profile/'.$comment_id) }}">Unfollow</a>
                                                        <?php
} else {?>
                                                        <a href="{{ url('/view_profile/'.$comment_id) }}"
                                                        >Follow</a>
                                                      <?php
}
} else if ($commentUser->id != Auth::user()->id) {?>
                                                        <a href="{{ url('/view_profile/'.$comment_id) }}"
                                                        >Follow</a>
                                                    <?php
}
?>
                                            </div>
                                            <div class="col-sm-10 user-rply">
                                                <div class="post-inner-reply">
                                                    <div class="pull-left post-user-nam">
                                                        <h3>{{ $comment->commentUser->name }}</h3>
                                                        <p>- on {{ date('d-m-Y',strtotime($comment->created_at)) }}</p>
                                                    </div>
                                                    @if($comment->commentUser->id == Auth::user()->id)
                                                        <div class="pull-right post-reply-pop">
                                                            <div class="options">
                                                                <div class="fmr-10">
                                                                    <a class="set-edit" onclick="editComment({{ $comment->id }});" href="javascript:void(0)">e</a>
                                                                    <a class="set-alarm deleteComment" data-comment-id="{{ $comment->id }}" href="javascript:void(0);">a</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                                <p class="text-12" >
                                                    <input style="display: none;" type="text" class="profanity" id="comment_text_{{ $comment->id }}" value="{{ $comment->comment_reply }}" readonly >
                                                    <p class="profanity" id="comment_disp_{{ $comment->id }}">{{ $comment->comment_reply }}</p>
                                                    <button style="display:none;" class="saveComment st-btn" onclick="updateComment({{ $comment->id }})" id="update_comment_{{ $comment->id }}">Save</button>
                                                </p>
                                                <div class="rply-box">
                                                    <div class="rply-count">
                                                        <a href="#myModal" data-toggle="modal"><img
                                                                    src="{{ asset('assets/img/post-rply.png') }}"
                                                                    alt="post-rply"> </a>
                                                        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog"
                                                             tabindex="-1" id="myModal" class="modal fade"
                                                             style="display: none;">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <button aria-hidden="true" data-dismiss="modal"
                                                                                class="desktop-close" type="button">×
                                                                        </button>
                                                                        <h4 class="modal-title">Reply to Comment</h4>
                                                                    </div>
                                                                    <form method="post" class="common-form">
                                                                        <div class="form-group">
                                                                            <label>Message To Author:</label>
                                                                            <textarea type="text" id="comment_reply_text_{{ $comment->id }}"
                                                                                      placeholder="Type here"></textarea>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <div class="btn-wrap-div">
                                                                                <input data-comment-id="{{ $comment->id }}" class="st-btn reply_to_comment" type="button"
                                                                                       value="Submit">
                                                                                <input value="Cancel" class="st-btn"
                                                                                       aria-hidden="true" data-dismiss="modal"
                                                                                       type="reset">
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div><!-- /.modal-content -->
                                                            </div><!-- /.modal-dialog -->
                                                        </div>
                                                        <p id="reply_count_{{ $comment->id }}">{{ count($comment->commentReply) }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="row" id="comment">
                                        <div class="col-sm-10 user-rply">
                                            <div class="post-inner-reply">
                                                <p>Currently there are no comments in this meeting.</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>
                    <div id="post-detail-right" class="col-sm-4">
                        <div class="category">
                            <h2>Members<span>({{ count($meeting_users) }} Invited)</span></h2>
                            <div class="post-category">
                                @foreach($meeting_users as $user)
                                    <div class="member-wrap">
                                        <div class="member-img">
                                            <?php
$path = 'assets/img/member1.PNG';
$admin_icon = asset('assets/img/member-icon.PNG');
$profile_picture = (!empty($user->profile_image)) ? asset('public/uploads/profile_pic/') . '/' . $user->profile_image : asset($path);?>
                                            <img src="{{ $profile_picture }}" alt="no">
                                            @if($user->is_admin == '1')
                                                <figure><img src="{{ $admin_icon }}" alt="member-icon"></figure>
                                            @endif
                                        </div>
                                        <div class="member-details">
                                            <h3 class="text-12"><a href="{{ url('view_profile/'.Helpers::encode_url($user->id)) }}">{{ $user->name }}</a></h3>
                                            <a href="#">{{ $user->email }}</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="category">
                            <h2>Uploaded Files</h2>
                            <div class="idea-grp post-category">
                                @if(count($uploadedFiles) > 0)
                                    @foreach($uploadedFiles as $attachment)
                                        <div class="member-wrap files-upload">
                                            <div class="member-img">
                                                <img src="{{ asset('assets/img/uploadfiles2.PNG') }}" alt="no">
                                            </div>
                                            <div class="member-details">
                                                <h3 class="text-12">{{ $attachment->original_file_name }}</h3>
                                                <p class="text-10">Uploaded By: <a href="#">{{ $attachment->attachmentUser->name }}</a></p>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="member-wrap files-upload">
                                        <p>No files has been attached yet.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@push('javascripts')
    <script type="text/javascript">
        $('#commentReply').on('show.bs.modal', function (event) {
            // Button that triggered the modal
            var button = $(event.relatedTarget);
            // Extract info from data-* attributes
            var comment_id = button.data('comment-id');
            // console.log("Comment ID: " + comment_id);

            var modal = $(this);

            // $('.reply_to_comment').unbind().click(function (e) {

        });
        $('.reply_to_comment').click(function (e) {
            e.preventDefault();
            // console.log("clicked"+Math.random());
            var comment_id = $(this).data('commentId');
            replyToComment(comment_id);
        });

        function replyToComment(comment_id) {
            var reply = $("#comment_reply_text_"+comment_id).val();
            var dataString = {_token: CSRF_TOKEN , reply_text: reply,comment_id:comment_id};
            // console.log(dataString);return false;
            $.ajax({
                type: "POST",
                url: "{{ route('replyToMeetingComment') }}",
                data: dataString,
                success: function (response) {
                    var reply_count = response.data.count;
                    $("#reply_count_"+comment_id).html(reply_count);
                    $("#myModal").modal('hide');
                    $("#comment_reply_text_"+comment_id).val('').html('');
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

        function editComment(id) {
            $('#comment_text_' + id).removeProp('readonly').slideDown('fast');
            $('#update_comment_' + id).css('display', 'block');
             $("#comment_disp_"+id).slideUp('fast');
        }

        function updateComment(id) {

                var comment = $.trim($('#comment_text_' + id).val());
                var _token = CSRF_TOKEN;
            if (comment != "" && comment.length > 0)
            {
                var formData = {id: id, comment: comment, _token: CSRF_TOKEN};
                $.ajax(
                {
                    url: "{{ route('updateMeetingComment') }}",
                    type: 'POST',
                    data: formData,
                    success: function (response)
                    {
                        var res = JSON.parse(response);
                        if (res.status == 1)
                        {
                            //swal("Success", res.msg, "success");
                            $('#comment_text_' + id).attr('readonly', true).slideUp('fast');
                            $('#update_comment_' + id).css('display', 'none');
                            $("#comment_disp_"+id).html(comment).slideDown('fast');

                            runProfanity();
                        } else {
                            swal("Error", res.msg, "error");
                        }
                    },
                    error: function (e) {
                        swal("Error", e, "error");
                    }
                });
            }
        }
        function comment_reply() {
            var commentid = $('#modalComment').attr('data-id');
            var _token = CSRF_TOKEN;
            var post_id = $('#post_id').val();
            var comment_reply = $('#comment_text_' + commentid).val();
            var anonymous = 0;
            var srno = $('#commentreply_' + commentid + ' .cmry:first').attr('id');
            if ($("#is_anonymous_" + commentid).is(':checked')) {
                anonymous = 1;
            } else {
                anonymous = 0;
            }
            var formData = {comment_id:commentid, comment_reply:comment_reply, post_id:post_id, is_anonymous:anonymous, srno:srno, _token};
            $.ajax({
                url: SITE_URL + '/comment_reply',
                type: 'POST',
                data: formData,
                success: function(response) {
                    /*res = JSON.parse(response);
                     if (res.status == 1) {
                     location.reload();
                     } else {
                     swal("Error", res.msg, "error");
                     }*/
                    console.log(commentid, "::::", srno);
                    $('#commentreply_' + commentid + ' #' + srno).before(response);
                },
                error: function(e) {
                    swal("Error", e, "error");
                }
            });
        }

        $(document).on('click','.deleteComment',function () {
            var comment_id = $(this).data('commentId');
            var dataString = {_token: CSRF_TOKEN , comment_id:comment_id};
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this comment!",
                icon: "warning",
                showCancelButton: true,
                closeOnConfirm: false,
                showLoaderOnConfirm: true
            },function(willDelete) {
                if (willDelete) {
                    $.ajax({
                        url: "{{ route('deleteMeetingComment') }}",
                        data: dataString,
                        type: "POST",
                        success: function(response)
                        {
                            var status = response.status;
                            var msg = response.msg;

                            if(status == '1')
                            {
                                swal("Success",msg,"success");
                                $("#comment_box_"+comment_id).remove();
                            } else {
                                swal("Error",msg,"error");
                            }

                        }
                    });
                }});
        });

    </script>


@endpush
