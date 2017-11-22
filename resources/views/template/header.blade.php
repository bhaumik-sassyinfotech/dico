<header class="navbar navbar-inverse navbar-fixed-top" role="banner">
        <a id="leftmenu-trigger" class="tooltips" data-toggle="tooltip" data-placement="right" title="Toggle Sidebar"></a>

        <div class="navbar-header pull-left">
            <a class="navbar-brand" href="{{url('/')}}">Dico</a>
        </div>
        <?php
            if(!empty(Auth::user()->profile_image)) {
                $profile_pic = 'public/uploads/profile_pic/'.Auth::user()->profile_image;
            }else {
                //$profile_pic = 'public/assets/demo/avatar/dangerfield.png';
                $profile_pic = 'public/assets/demo/avatar/jackson.png';
            }
        ?>

        <ul class="nav navbar-nav pull-right toolbar">
        	<li class="dropdown">
        		<a href="#" class="dropdown-toggle username" data-toggle="dropdown"><span class="hidden-xs"><?php echo Auth::user()->name;?> <i class="fa fa-caret-down"></i></span><img src="{{asset($profile_pic)}}" alt="" /></a>
        		<ul class="dropdown-menu userinfo arrow">
        			<li class="username">
                        <a href="#">
        				    <div class="pull-left"><img src="{{asset($profile_pic)}}" alt=""/></div>
        				    <div class="pull-right"><h5><?php echo Auth::user()->name;?></h5><small>Logged in as <span><?php echo Auth::user()->name;?></span></small></div>
                        </a>
        			</li>
        			<li class="userlinks">
        				<ul class="dropdown-menu">
        					<li><a href="{{url('edit_profile')}}">Edit Profile <i class="pull-right fa fa-pencil"></i></a></li>
                                                <li style="display: none;"><a href="#">Account <i class="pull-right fa fa-cog"></i></a></li>
        					<li style="display: none;"><a href="#">Help <i class="pull-right fa fa-question-circle"></i></a></li>
        					<li class="divider"></li>
        					<li><a href="{{ route('logout') }}"  onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="text-right">Sign Out </a>
                                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                        {{ csrf_field() }}
                                                    </form>
                                                </li>
        				</ul>
        			</li>
        		</ul>
        	</li>
        	
        	<li class="dropdown">
        		<a href="#" class="hasnotifications dropdown-toggle" data-toggle='dropdown'><i class="fa fa-bell"></i><span class="badge"></span></a>
        		<ul class="dropdown-menu notifications arrow">
        			<li class="dd-header">
        				<span>You have 3 new notification(s)</span>
        				<span><a href="#">Mark all Seen</a></span>
        			</li>
                                <div class="scrollthis">
    				    <li>
    				    	<a href="#" class="notification-user active">
    				    		<span class="time">4 mins</span>
    				    		<i class="fa fa-user"></i>
    				    		<span class="msg">New user Registered. </span>
    				    	</a>
    				    </li>
                                </div>
        			<li class="dd-footer"><a href="#">View All Notifications</a></li>
				</ul>
			</li>
		</ul>
    </header>