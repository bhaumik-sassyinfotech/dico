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

        <li><a href="javascript:;"><i class="fa fa-building-o"></i> <span>Company</span></a>
            <ul class="acc-menu">
                <li><a href="{{ route('company.create') }}"><span>Add Company</span></a></li>
                <li><a href="{{ route('company.index') }}"><span>View Company</span></a></li>
            </ul>
        </li>
        <li><a href="javascript:;"><i class="fa fa-lock"></i> <span>Security Question</span></a>
            <ul class="acc-menu">
                <li><a href="{{ route('security_question.create') }}"><span>Add Security Question</span></a></li>
                <li><a href="{{ route('security_question.index') }}"><span>View Security Question</span></a></li>
            </ul>
        </li>
        <li><a href="javascript:;"><i class="fa fa-lock"></i> <span>User</span></a>
            <ul class="acc-menu">
                <li><a href="{{ route('user.create') }}"><span>Add User</span></a></li>
                <li><a href="{{ route('user.index') }}"><span>View User</span></a></li>
            </ul>
        </li>

        <li><a href="javascript:;"><i class="fa fa-users"></i> <span>Group</span></a>
            <ul class="acc-menu">
                <li><a href="{{ route('group.create') }}"><span>Add Group</span></a></li>
                <li><a href="{{ route('group.index') }}"><span>View Groups</span></a></li>
            </ul>
        </li>
        <?php /*<li><a href="{{ route('post.index') }}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i><span>Post</span></a></li>*/?>
        <li><a href="javascript:;"><i class="fa fa-trophy"></i> <span>Points</span></a>
            <ul class="acc-menu">
                <li><a href="{{ route('points.viewList') }}"><span>View Points</span></a></li>
                <li><a href="{{ route('points.index') }}"><span>Edit Points</span></a></li>
            </ul>
        </li>
        <!-- <li><a href="{{ route('points.index') }}"><i class="fa fa-usd" aria-hidden="true"></i><span>Points</span></a> -->
        <!--</li>-->
        <li><a href="javascript:;"><i class="fa fa-external-link"></i> <span>Blog</span></a>
            <ul class="acc-menu">
                <li><a href="{{ route('blog.index') }}"><span>View Blog</span></a></li>
                
            </ul>
        </li>
        <li><a href="javascript:;"><i class="fa fa-gift"></i> <span>Package</span></a>
            <ul class="acc-menu">
                <li><a href="{{ route('packages.index') }}"><span>View Package</span></a></li>
                
            </ul>
        </li>
        <li><a href="javascript:;"><i class="fa fa-question"></i> <span>FAQs</span></a>
            <ul class="acc-menu">
                <li><a href="{{ route('adminfaq.index') }}"><span>View FAQs</span></a></li>
                
            </ul>
        </li>
        <li><a href="javascript:;"><i class="fa fa-thumbs-o-up"></i> <span>Feedback</span></a>
            <ul class="acc-menu">
                <li><a href="{{ route('feedback.index') }}"><span>View Feedback</span></a></li>
                
            </ul>
        </li>
          <li><a href="javascript:;"><i class="fa fa-info"></i> <span>Support</span></a>
            <ul class="acc-menu">
                <li><a href="{{ route('support.index') }}"><span>View Support</span></a></li>
                
            </ul>
        </li>
          <li><a href="javascript:;"><i class="fa fa-adjust"></i> <span>Contact</span></a>
            <ul class="acc-menu">
                <li><a href="{{ route('adminContactUs.index') }}"><span>View Contact</span></a></li>
                
            </ul>
        </li>
          <li><a href="javascript:;"><i class="fa fa-gear"></i> <span>Settings</span></a>
            <ul class="acc-menu">
                <li><a href="{{ route('settings.index') }}"><span>View Settings</span></a></li>
                
            </ul>
        </li>
        <?php
}
//=====end superadmin menu=====//
//=====start company admin menu=====//
else if ($role_id == 2) {
	?>
        <li><a href="javascript:;"><i class="fa fa-lock"></i> <span>User</span></a>
            <ul class="acc-menu">
                <?php
/*
	<li><a href="{{ route('user.create') }}"><span>Add User</span></a></li>
	 */
	?>
                <li><a href="{{ route('user.create') }}"><span>Add User</span></a></li>
                <li><a href="{{ route('user.index') }}"><span>View User</span></a></li>
            </ul>
        </li>
        <li style="display: none;"><a href="{{ route('employee.index') }}"><i class="fa fa-home"></i>
                <span>Employee</span></a></li>
        <li><a href="javascript:;"><i class="fa fa-users"></i> <span>Group</span></a>
            <ul class="acc-menu">
                <li><a href="{{ route('group.create') }}"><span>Add Group</span></a></li>
                <li><a href="{{ route('group.index') }}"><span>View Groups</span></a></li>
            </ul>
        </li>
        <li><a href="javascript:;"><i class="fa fa-trophy"></i> <span>Points</span></a>
            <ul class="acc-menu">
                <li><a href="{{ route('points.viewList') }}"><span>View Points</span></a></li>
                <li><a href="{{ route('points.index') }}"><span>Edit Points</span></a></li>
            </ul>
        </li>
        <li><a href="{{ route('post.index') }}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i><span>Post</span></a></li>
        <?php }
//=====end company admin menu=====//
//=====start employee menu=====//
else if ($role_id == 3) {
	?>
        <li><a href="{{ route('post.index') }}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i><span>Post</span></a></li>
        <li><a href="javascript:;"><i class="fa fa-lock"></i> <span>User</span></a>
            <ul class="acc-menu">
                <li><a href="{{ route('user.index') }}"><span>View User</span></a></li>
            </ul>
        </li>
        <li><a href="{{ route('group.index') }}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i><span>Group</span></a></li>
        <?php
}
//=====start employee menu=====//
?>
        @if($role_id > 1)
            <li><a href="javascript:;"><i class="fa fa-users"></i> <span>Meetings</span></a>
                <ul class="acc-menu">
                    <li><a href="{{ route('meeting.create') }}"><span>Create Meeting</span></a></li>
                    <li><a href="{{ route('meeting.index') }}"><span>View Meetings</span></a></li>
                </ul>
            </li>
        @endif

    </ul>
    <!-- END SIDEBAR MENU -->
    <?php if ($role_id == 2 || $role_id == 3) { ?>
        <div class="bottom-sidebar">
            <div class="bottom-logo"><a href="#"><i><img src="{{asset('assets/img/fevicon.png')}}" ></i><span> <img src="{{asset('assets/img/logo.png')}}"></span></a></div>
            <div class="feedback"><a href="{{route('feedback.create')}}">
                    <i class="fa fa-check-square" aria-hidden="true"></i><span>Give Us feedback </span></a></div>
            <div class="support"><a href="{{route('support.create')}}"><i class="fa fa-comments-o" aria-hidden="true"></i> <span>Support</span></a></div>        
        </div>
<?php } ?>
</nav>