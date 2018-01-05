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
    <div id="page-content" class="idea-details padding-box post-details">
        <div id='wrap'>
            <div id="page-heading">
                <ol class="breadcrumb">
                    <li><a href="{{ url('/home') }}">Dashboard</a></li>
                    <li><a href="{{ route('post.index') }}">Post</a></li>
                    <li class="active">View Post</li>
                </ol>
                <h1 class="icon-mark tp-bp-0">Idea Post</h1>
                <hr class="border-out-hr">
            </div>
            <div class="container">
                <div class="row">
                    <div id="post-detail-left" class="col-sm-8">
                        <div class="group-wrap">   
                            <div class="pull-left">
                                <h3>{{$post->post_title}}</h3>
                                <p class="user-icon">-{{$post->postUser->name}}<span>on {{date('d/m/Y',strtotime($post->created_at))}}</span></p>
                            </div>
                            <div class="pull-right">
                                <div class="options">
                                    <div class="fmr-10">
                                        <a class="set-alarm" href="">a</a>
                                        <?php
                                            if ($post['user_id'] == Auth::user()->id) {
                                        ?>
                                        <a class="set-edit" href="{{route('idea.edit',$post->id)}}">w</a>
                                        <?php
                                            }
                                        ?>
                                        <a class="set-delete" href="">w</a>
                                    </div>
                                </div>
                            </div>  
                        </div> 
                        <div class="post-wrap-details"> 
                            <p class="text-12">{{$post->post_description}}</p>
                            <form name="post_comment_form" id="post_comment_form" method="post" action="{{url('savecomment',$post->id)}}">
                            {{ csrf_field() }}
                            <input type="hidden" name="post_id" id="post_id" value="{{ $post->id }}">
                            <div class="post-details-like">
                                <div class="like">
                                    <a href="javascript:void(0)" id="like_post" onclick="likePost({{$post['id']}})">
                                        <?php
                                            if(!empty($post[ 'postUserLike' ])) {
                                        ?>
                                                <i class="fa fa-thumbs-up"></i>
                                        <?php } else { ?>
                                                <i class="fa fa-thumbs-o-up"></i>
                                        <?php } ?>
                                    </a>
                                    <span id="post_like_count"><?php echo count($post[ 'postLike' ]);?></span>
                                </div>
                                <div class="unlike">
                                    <a href="javascript:void(0)" id="dislike_post" onclick="dislikePost({{$post['id']}})">
                                        <?php
                                            if(!empty($post[ 'postUserUnLike' ])) {
                                        ?>
                                                <i class="fa fa-thumbs-down" aria-hidden="true"></i>
                                        <?php } else { ?>
                                                <i class="fa fa-thumbs-o-down" aria-hidden="true"></i>
                                        <?php } ?>
                                    </a>
                                    <span id="post_dislike_count"><?php echo count($post[ 'postUnLike' ]);?></span>
                                </div>
                            </div>
                            <div class="post-btn-wrap">
                                @if($post->idea_status == null && $currUser->role_id < 3)
                                <div class="post-btn approve"> 
                                    <a href="javascript:void(0)" class="ideaStatus" data-post-status="approve">Approve</a>
                                </div>
                                <div class="post-btn deny"> 
                                    <a href="javascript:void(0)" class="ideaStatus" data-post-status="deny">Denied</a>
                               </div>
                               <div class="post-btn ammend"> 
                                    <a href="javascript:void(0)" class="ideaStatus" data-post-status="amend">Amend</a>
                               </div>
                                @else
                                    @if($post->idea_status == null)
                                        <div class="col-md-6">Action pending</div>
                                    @else
                                        @php
                                            $ideaStatus = '';
                                            if($post->idea_status === 'approve')
                                                $ideaStatus = 'Approved';
                                            else if($post->idea_status === 'deny')
                                                $ideaStatus = 'Denied';
                                            else if($post->idea_status === 'amend')
                                                $ideaStatus = 'Amended';
                                        @endphp
                                        <div class="col-md-6">{{ $ideaStatus }} by: <strong>{{ $post->ideaUser->name }}</strong></div>

                                    @endif
                                @endif
                            </div>
                            <div class="approved">
                                <!-- <p>Approved By:<span>Jason Belmonte</span></p>-->
                           </div>
                        </div>
                    </div>
                    <div class="col-sm-4" id="post-detail-right">
                       <div class="category">
                            <h2>Group</h2>
                            <div class="idea-grp post-category">
                                <div class="member-wrap">
                                    <div class="member-img">
                                        <img src="assets/img/custome-service.png" alt="no">
                                    </div>
                                    <div class="member-details">
                                        <h3 class="text-12">Marketing</h3>
                                        <p class="text-10">Members: <span>24</span></p>
                                    </div>
                                </div>
                                <div class="member-wrap">
                                    <div class="member-img">
                                        <img src="assets/img/business-development.png" alt="no">
                                    </div>
                                    <div class="member-details">
                                        <h3 class="text-12">Business Development</h3>
                                        <p class="text-10">Members: <span>26</span></p>
                                    </div>
                                </div> 
                                <div class="member-wrap">
                                    <div class="member-img">
                                        <img src="assets/img/marketing.png" alt="no">
                                     </div>
                                    <div class="member-details">
                                        <h3 class="text-12">Human Resource</h3>
                                        <p class="text-10">Members: <span>06</span></p>
                                    </div>
                                </div>   
                             </div>  
                          </div>
                         <div class="category">
                            <h2>Tags</h2>
                            <div class="post-circle post-category">
                                <a href="#"> Dummy</a><a href="#">Lorem lpsum</a><a href="#">Cuckooâ€™s Nest</a><a href="#">Flew</a><a href="#">Lane Del Rey</a><a href="#">jane waterman</a>
                             </div>  
                          </div>  
                         
                          <div class="category">
                            <h2>Similar Posts</h2>
                            <div class="post-links">
                                <a href="#">who has any right to find fault with....</a>
                                <a href="#">At vero eos et accusamus et iusto....</a>
                                <a href="#">Nam libero tempore, cum soluta nobis....</a>
                                <a href="#">placeat facere possimus, omnis voluptas...</a>
                             </div>  
                          </div> 
                        <div class="category">
                            <h2>Uploaded Files</h2>
                           <div class="idea-grp post-category">
                                
                                <div class="member-wrap files-upload">
                                    <div class="member-img">
                                        <img alt="no" src="assets/img/uploadfiles1.PNG">
                                    </div>
                                    <div class="member-details">
                                        <h3 class="text-12">Sales Report.Pdf</h3>
                                        <p class="text-10">Uploaded By: <a href="#">Jason Mcdowney</a></p>
                                    </div>
                                </div>
                                <div class="member-wrap files-upload">
                                    <div class="member-img">
                                        <img alt="no" src="assets/img/uploadfiles2.PNG">
                                    </div>
                                    <div class="member-details">
                                        <h3 class="text-12">Managament List.jpeg</h3>
                                        <p class="text-10">Uploaded By: <a href="#">Jason Mcdowney</a></p>
                                    </div>
                                </div> 
                               <div class="member-wrap files-upload">
                                    <div class="member-img">
                                        <img alt="no" src="assets/img/uploadfiles3.PNG">
                                    </div>
                                    <div class="member-details">
                                        <h3 class="text-12">Attendance</h3>
                                        <p class="text-10">Uploaded By: <a href="#">Jason Mcdowney</a></p>
                                    </div>
                                </div>
                               <div class="member-wrap files-upload">
                                    <div class="member-img">
                                        <img alt="no" src="assets/img/uploadfiles4.PNG">
                                    </div>
                                    <div class="member-details">
                                        <h3 class="text-12">Expense Sheet.Xlxs</h3>
                                        <p class="text-10">Uploaded By:<a href="#">Jason Mcdowney</a></p>
                                    </div>
                                </div>
                             </div> 
                          </div>       

                        </div>
                        <?php /*<div class="panel-footer">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xs-12 btn-toolbar">
                                        <a href="{{route('post.index')}}" class="btn btn-default">Back</a>
                                        @if($currUser->id == $post->user_id)
                                            <a href="{{route('idea.edit',$post->id)}}" class="btn btn-primary">Edit</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>*/?>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop