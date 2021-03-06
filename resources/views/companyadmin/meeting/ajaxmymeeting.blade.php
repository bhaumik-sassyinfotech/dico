<?php 
    if(!empty($myMeetings)) {
        foreach($myMeetings as $meeting) {
            $class = '';
            $type = '';
            if ( $meeting->privacy == '1' ) { //private
                $class = 'meetings-2';
                $type = 'Private';
            } else {
                $class = 'meetings-1';
                $type = 'Public';
            }
?>
    <div class="col-md-4 mymeetinglist">
        <div class="{{ $class }} panel-primary">
            <div class="panel-heading">
                <h4 class="icon">{{ $type }} @lang("label.Meeting")</h4>
                <div class="pull-right">
                    <a href="#"> <i class="fa fa-bell-o" aria-hidden="true"></i></a>
                    <!-- <a href="#"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i></a>-->
                </div>
            </div>
            <div class="panel-body">
                <h4><a href="{{ route('meeting.show',[Helpers::encode_url($meeting->id)]) }}" class="profanity post-title">{{ $meeting->meeting_title }}</a></h4>
                <div class="user-wrap">
                    <div class="user-img"> 
                        @if(empty($meeting->meetingCreator->profile_image))
                            <img src="{{ asset(DEFAULT_PROFILE_IMAGE) }}">
                        @else
                        <img src="{{ asset(PROFILE_PATH.$meeting->meetingCreator->profile_image) }}">
                        @endif
                    </div>
                    <p class="user-icon"> - <a href="{{route('view_profile', Helpers::encode_url($meeting->meetingCreator->id))}}" class="user-a-post">{{ $meeting->meetingCreator->name }}</a><span>@lang("label.on") {{ date(DATE_FORMAT,strtotime($meeting->created_at)) }}</span></p>
                </div>
                <fieldset>
                    <p class="text-12 desc-content" id="desc_mycontent_{{$meeting->id}}">{{ nl2br($meeting->meeting_description) }}</p>
                </fieldset>
                <?php
                if(strlen($meeting->meeting_description) > POST_DESCRIPTION_LIMIT) {
                ?>
                <div class="btn-wrap" id="meetingread{{$meeting->id}}">
                    <a href="javascript:void(0)" onclick="ReadMore('desc_mycontent_{{$meeting->id}}','meetingread{{$meeting->id}}')">@lang("label.ReadMore")</a>
                </div>
                <?php } ?>
                <hr>
                <div class="panel-body-wrap">
                    <div class="member pull-left">
                        <p>@lang("label.Members"):<span>{{ $meeting->meeting_users_count }}</span></p>
                    </div>
                    <div class="status pull-right">
                        <?php
                        if($meeting->is_finalized == '0')
                        {
                            $cls = 'active';
                            $txt = 'Active';
                        }else{
                            $cls = 'inactive error';
                            $txt = 'Finalized';
                        }
                        ?>
                        <p>@lang("label.Status"):<span class="{{ $cls }}">{{ $txt }}</span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php 
            }
        } else {
            echo __("label.Nomeetingfound");
        }
    ?>
