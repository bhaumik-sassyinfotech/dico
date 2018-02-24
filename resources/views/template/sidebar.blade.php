<nav id="page-leftbar" role="navigation">
    <!-- BEGIN SIDEBAR MENU -->
    <ul class="acc-menu" id="sidebar">
        <li id="search" style="display: none;">
            <a href="javascript:;"><i class="fa fa-search opacity-control"></i></a>
            <form>
                <input type="text" class="search-query" placeholder="Search...">
                <button type="submit"><i class="fa fa-search"></i></button>
            </form>
        </li>
        <?php $currUser = Auth::user();
              $role_id = $currUser->role_id;
        ?>
        <li style="display: none;" class="divider"></li>
        <?php
//=====start superadmin menu=====//
if ($role_id == 1) {
	?>

        <li><a href="javascript:;"><i class="fa fa-building-o"></i> <span>@lang("label.adCompany")</span></a>
            <ul class="acc-menu">
                <li><a href="{{ route('company.create') }}"><span>@lang("label.adAdd Company")</span></a></li>
                <li><a href="{{ route('company.index') }}"><span>@lang("label.adView Company")</span></a></li>
            </ul>
        </li>
        <li><a href="javascript:;"><i class="fa fa-lock"></i> <span>@lang("label.adSecurity Question")</span></a>
            <ul class="acc-menu">
                <li><a href="{{ route('security_question.create') }}"><span>@lang("label.adAdd Security Question")</span></a></li>
                <li><a href="{{ route('security_question.index') }}"><span>@lang("label.adView Security Question")</span></a></li>
            </ul>
        </li>
        <li><a href="javascript:;"><i class="fa fa-lock"></i> <span>@lang("label.adUser")</span></a>
            <ul class="acc-menu">
                <li><a href="{{ route('user.create') }}"><span>@lang("label.adAdd User")</span></a></li>
                <li><a href="{{ route('user.index') }}"><span>@lang("label.adView User")</span></a></li>
            </ul>
        </li>

        <li><a href="javascript:;"><i class="fa fa-users"></i> <span>@lang("label.adGroup")</span></a>
            <ul class="acc-menu">
                <li><a href="{{ route('group.create') }}"><span>@lang("label.adAdd Group")</span></a></li>
                <li><a href="{{ route('group.index') }}"><span>@lang("label.adView Groups")</span></a></li>
            </ul>
        </li>
        <?php /*<li><a href="{{ route('post.index') }}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i><span>Post</span></a></li>*/?>
        <li><a href="javascript:;"><i class="fa fa-trophy"></i> <span>@lang("label.adPoints")</span></a>
            <ul class="acc-menu">
                <li><a href="{{ route('points.viewList') }}"><span>@lang("label.adView Points")</span></a></li>
                <li><a href="{{ route('points.index') }}"><span>@lang("label.adEdit Points")</span></a></li>
            </ul>
        </li>
        <!-- <li><a href="{{ route('points.index') }}"><i class="fa fa-usd" aria-hidden="true"></i><span>Points</span></a> -->
        <!--</li>-->
        <li><a href="javascript:;"><i class="fa fa-external-link"></i> <span>@lang("label.adBlog")</span></a>
            <ul class="acc-menu">
                <li><a href="{{ route('blog.index') }}"><span>@lang("label.adView Blog")</span></a></li>
                
            </ul>
        </li>
        <li><a href="javascript:;"><i class="fa fa-gift"></i> <span>@lang("label.adPackage")</span></a>
            <ul class="acc-menu">
                <li><a href="{{ route('packages.index') }}"><span>@lang("label.adView Package")</span></a></li>
                
            </ul>
        </li>
        <li><a href="javascript:;"><i class="fa fa-question"></i> <span>@lang("label.adFAQs")</span></a>
            <ul class="acc-menu">
                <li><a href="{{ route('adminfaq.index') }}"><span>@lang("label.adView FAQs")</span></a></li>
                
            </ul>
        </li>
        <li><a href="javascript:;"><i class="fa fa-thumbs-o-up"></i> <span>@lang("label.adFeedback")</span></a>
            <ul class="acc-menu">
                <li><a href="{{ route('feedback.index') }}"><span>@lang("label.adView Feedback")</span></a></li>
                
            </ul>
        </li>
          <li><a href="javascript:;"><i class="fa fa-info"></i> <span>@lang("label.adSupport")</span></a>
            <ul class="acc-menu">
                <li><a href="{{ route('support.index') }}"><span>@lang("label.adView Support")</span></a></li>
                
            </ul>
        </li>
          <li><a href="javascript:;"><i class="fa fa-adjust"></i> <span>@lang("label.adContact")</span></a>
            <ul class="acc-menu">
                <li><a href="{{ route('adminContactUs.index') }}"><span>@lang("label.adView Contact")</span></a></li>
                
            </ul>
        </li>
          <li><a href="javascript:;"><i class="fa fa-gear"></i> <span>@lang("label.adSettings")</span></a>
            <ul class="acc-menu">
                <li><a href="{{ route('settings.index') }}"><span>@lang("label.adView Settings")</span></a></li>
                
            </ul>
        </li>
          <li><a href="javascript:;"><i class="fa fa-gear"></i> <span>@lang("label.adNotification")</span></a>
            <ul class="acc-menu">
                <li><a href="{{ route('notification.index') }}"><span>@lang("label.adNotification")</span></a></li>                
            </ul>
        </li>
        <?php
}
//=====end superadmin menu=====//
//=====start company admin menu=====//
else if ($role_id == 2) {
	?>
        <li><a href="javascript:;"><i class="fa fa-lock"></i> <span>@lang("label.adUser")</span></a>
            <ul class="acc-menu">
                <?php
/*
	<li><a href="{{ route('user.create') }}"><span>Add User</span></a></li>
	 */
	?>
                <li><a href="{{ route('user.create') }}"><span>@lang("label.adAdd User")</span></a></li>
                <li><a href="{{ route('user.index') }}"><span>@lang("label.adView User")</span></a></li>
                <li><a href="{{ route('companyEdit') }}"><span>@lang("label.adCompany Detail")</span></a></li>
            </ul>
        </li>
        <li style="display: none;"><a href="{{ route('employee.index') }}"><i class="fa fa-home"></i>
                <span>Employee</span></a></li>
        <li><a href="javascript:;"><i class="fa fa-users"></i> <span>@lang("label.adGroup")</span></a>
            <ul class="acc-menu">
                <li><a href="{{ route('group.create') }}"><span>@lang("label.adAdd Group")</span></a></li>
                <li><a href="{{ route('group.index') }}"><span>@lang("label.adView Groups")</span></a></li>
            </ul>
        </li>
        <li><a href="javascript:;"><i class="fa fa-trophy"></i> <span>@lang("label.adPoints")</span></a>
            <ul class="acc-menu">
                <li><a href="{{ route('points.viewList') }}"><span>@lang("label.adView Points")</span></a></li>
                <li><a href="{{ route('points.index') }}"><span>@lang("label.adEdit Points")</span></a></li>
            </ul>
        </li>
        <li><a href="{{ route('post.index') }}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i><span>@lang("label.adPost")</span></a></li>
        <?php }
//=====end company admin menu=====//
//=====start employee menu=====//
else if ($role_id == 3) {
	?>
        <li><a href="{{ route('post.index') }}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i><span>@lang("label.adPost")</span></a></li>
        <li><a href="javascript:;"><i class="fa fa-lock"></i> <span>@lang("label.adUser")</span></a>
            <ul class="acc-menu">
                <li><a href="{{ route('user.index') }}"><span>@lang("label.adView User")</span></a></li>                
            </ul>
        </li>
        <li><a href="{{ route('group.index') }}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i><span>@lang("label.adGroup")</span></a></li>
        <?php
}
//=====start employee menu=====//
?>
        @if($role_id > 1)
            <li><a href="javascript:;"><i class="fa fa-users"></i> <span>@lang("label.adMeetings")</span></a>
                <ul class="acc-menu">
                    <li><a href="{{ route('meeting.create') }}"><span>@lang("label.adCreate Meeting")</span></a></li>
                    <li><a href="{{ route('meeting.index') }}"><span>@lang("label.adView Meetings")</span></a></li>
                </ul>
            </li>
        @endif

    </ul>
    <!-- END SIDEBAR MENU -->
    <?php if ($role_id == 2 || $role_id == 3) { ?>
        <div class="bottom-sidebar">
            <div class="bottom-logo"><a href="#"><i><img src="{{asset('assets/img/fevicon.png')}}" ></i><span> <img src="{{asset('assets/img/logo.png')}}"></span></a></div>
            <div class="feedback"><a href="{{route('feedback.create')}}">
                    <i class="fa fa-check-square" aria-hidden="true"></i><span>@lang("label.adGive Us feedback") </span></a></div>
            <div class="support"><a href="{{route('support.create')}}"><i class="fa fa-comments-o" aria-hidden="true"></i> <span>@lang("label.adSupport")</span></a></div>        
        </div>
<?php } ?>
</nav>