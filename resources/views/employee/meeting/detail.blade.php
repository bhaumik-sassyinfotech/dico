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
                                @foreach($meeting->meetingComment as $comment)
                                <div class="row">
                                    
                                    <div class="col-sm-2 user-image">
                                        <div class="img-wrap">
                                            <?php
                                                $profile_img = '';
                                                //$profile_pic = $comment->commentUser->profile_image;
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
                                                    <div class="star-wrap">
                                                                    <p class="active">Solution</p>
                                                    </div>
                                                    <div class="fmr-10">
                                                          <a class="set-warning" href="">w</a>
                                                          @if($comment->commentUser->id == Auth::user()->id)
                                                            <a class="set-edit" href="">e</a>
                                                            <a class="set-alarm" href="{{ url('meeting/deleteComment/'.$comment->id) }}">a</a>
                                                          @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="text-12">
                                            {{ $comment->comment_reply }}
                                        </p>
                                        <div class="rply-box">
                                        <a href="#" class="rply-count like">
                                            <img src="{{asset('assets/img/like.png')}}" alt="post-like"><p>08</p>
                                        </a>
                                        <a href="#" class="rply-count dislike">
                                            <img src="{{asset('assets/img/like.png')}}" alt="post-like"><p>08</p>
                                        </a>
                                        <div class="rply-count">
                                                <a href="#myModal" data-toggle="modal"><img src="{{asset('assets/img/post-rply.png')}}" alt="post-rply"> </a>
                                                <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade" style="display: none;">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <button aria-hidden="true" data-dismiss="modal" class="desktop-close" type="button"></button>
                                                                        <h4 class="modal-title">Report Comment</h4>
                                                                    </div>
                                                                    <form method="post" class="common-form">
                                                                     <div class="form-group">
                                                                        <label>Message To Author:</label> 
                                                                         <textarea type="text" placeholder="Type here"></textarea>
                                                                     </div> 
                                                                     <div class="form-group">
                                                                         <div class="btn-wrap-div">
                                                                              <input class="st-btn" type="submit" value="Submit">
                                                                              <input value="Cancel" class="st-btn" aria-hidden="true" data-dismiss="modal" type="reset">
                                                                         </div>     
                                                                     </div>     
                                                                    </form>
                                                                </div><!-- /.modal-content -->
                                                            </div><!-- /.modal-dialog -->
                                                         </div>
                                                <p>4</p>  
                                            </div>    
                                    </div>
                                        <?php /*<div class="rply-box">
                                            <div class="rply-count">
                                                <a href="#myModal" data-toggle="modal"><img src="assets/img/post-rply.png" alt="post-rply"> </a>
                                                <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade" style="display: none;">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button aria-hidden="true" data-dismiss="modal"
                                                                        class="desktop-close" type="button">×
                                                                </button>
                                                                <h4 class="modal-title">Report Comment</h4>
                                                            </div>
                                                            <form method="post" class="common-form">
                                                                <div class="form-group">
                                                                    <label>Message To Author:</label>
                                                                    <textarea type="text" placeholder="Type here"></textarea>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="btn-wrap-div">
                                                                        <input class="st-btn" type="submit"
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
                                                <p>4</p>
                                            </div>
                                        </div>*/?>
                                    </div>
                                </div>
                                @endforeach
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
                            //dd($post->postAttachment);
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
    </script>
@endpush
