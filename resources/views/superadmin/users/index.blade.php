@extends('template.default')
<title>DICO - User</title>
@section('content')

    <div id="page-content" class="main-user-profile user-profile point-page all-group-list  super-user-employee">
        <div id='wrap'>
            <div id="page-heading">
                <ol class="breadcrumb">
                    <li><a href="{{ url('/home') }}">Dashboard</a></li>
                    <li class="active">User</li>
                </ol>
                <h1 class="tp-bp-0">Users</h1>
                <div class="options">
                    <div class="btn-toolbar">
                        <a class="btn btn-default" href="create-user.php">
                            <i aria-hidden="true" class="fa fa-pencil-square-o fa-6"></i>
                            <span class="hidden-xs hidden-sm">Create User</span>
                        </a>
                        <a class="btn btn-default">
                            <i class="fa fa-sort fa-6" aria-hidden="true"></i>
                            <span class="hidden-xs hidden-sm">Sort</span>
                        </a>
                        <div class="btn-group">
                            <div class="btn-group color-changes">
                                <a data-toggle="dropdown" class="btn btn-default dropdown-toggle" href="#"><i
                                            aria-hidden="true" class="fa fa-filter fa-6"></i><span
                                            class="hidden-xs hidden-sm">Filter</span> </a>
                                <ul class="dropdown-menu">
                                    <li><a href="#">Notification off</a></li>
                                    <li><a href="#">Edit Post</a></li>
                                    <li><a href="#">Delete Post</a></li>
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    @include('template.notification')
                    <div class="col-lg-12">
                        <div class="panel panel-danger">
                            <div class="panel-body no-gapping">
                                <div class="row no-gapping">

                                    <div class="col-md-12 no-gapping">
                                        <div class="border-tabs">
                                            <div class="btn-toolbar form-group clearfix">
                                                <ul class="nav nav-tabs">
                                                    <li class="active"><a href="#employee" data-toggle="tab"
                                                                          data-order="desc" data-sort="default"
                                                                          class="btn btn-default sort active"><i
                                                                    class="fa fa-list visible-xs icon-scale"></i><span
                                                                    class="hidden-xs">Employee</span></a></li>
                                                    <li class=""><a href="#users" data-toggle="tab" data-order="desc"
                                                                    data-sort="data-name"
                                                                    class="btn btn-default sort"><span class=""><i
                                                                        class="fa fa-user fa-6 visible-xs"
                                                                        aria-hidden="true"></i><span class="hidden-xs">Group Admin</span></a>
                                                    </li>
                                                    <li class=""><a href="#other-managers" data-toggle="tab"
                                                                    data-order="asc" data-sort="data-name"
                                                                    class="btn btn-default sort"><span class=""><i
                                                                        class="fa fa-group visible-xs" aria=""
                                                                        hidden="true"></i><span class="hidden-xs">Other Managers</span></a>
                                                    </li>
                                                </ul>

                                                <div class="btn-group top-set">
                                                    <form method="post" class="search-form">
                                                        <input type="text" placeholder="Search User"/>
                                                        <input type="button" value="#" class="search-icon"/>
                                                    </form>
                                                    <button id="GoList" class="grid-view">
                                                        <img src="assets/img/icon/group-list.png" alt="group"
                                                             class="block">
                                                        <img src="assets/img/icon/group-lis-hover.png" alt="group"
                                                             class="none" style="display:none">
                                                    </button>
                                                    <button id="GoGrid" class="grid-view active">
                                                        <img src="assets/img/icon/grid-view.png" alt="group list"
                                                             class="block">
                                                        <img src="assets/img/icon/grid-view-hover.png" alt="group list"
                                                             class="none" style="display:none">

                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <ul class="gallery list-unstyled">

                                            <li data-name="Rusty" class="mix industrial mix_all"
                                                style="display: inline-block;  opacity: 1;">

                                                <div class="list-block super-user">
                                                    <div class="panel-heading">
                                                        <div class="pull-right">
                                                            <a href="#"><i aria-hidden="true" class="fa fa-bell-o"></i></a>
                                                            <a href="#"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                                            <a href="#"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                                        </div>

                                                    </div>
                                                    <div class="panel-body">
                                                        <fieldset>
                                                            <div class="grid-image">
                                                                <img src="assets/img/super-user.PNG" alt="super-user">
                                                            </div>
                                                            <div class="grid-details">
                                                                <h4>Jason Belmonte</h4>
                                                                <a href="mailto:jason_belmonte24@gmail.com">jason_belmonte24@gmail.com</a>
                                                                <h4>Super User</h4>
                                                            </div>

                                                        </fieldset>

                                                        <div class="btn-wrap">
                                                            <a href="#">Follow</a>
                                                            <a href="#">Point:246</a>

                                                        </div>
                                                        <div class="panel-body-wrap">
                                                            <div class="follower-text pull-left">
                                                                <p>Followers:<span>63</span></p>
                                                            </div>
                                                            <div class="follower-text pull-right">
                                                                <p>Following:<span>215</span></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>

                                            <li data-name="Enchanted Creek" class="mix nature mix_all"
                                                style="display: inline-block;  opacity: 1;">
                                                <div class="list-block admin">
                                                    <div class="panel-heading">
                                                        <div class="pull-right">
                                                            <a href="#"><i aria-hidden="true" class="fa fa-bell-o"></i></a>
                                                            <a href="#"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                                            <a href="#"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                                        </div>

                                                    </div>
                                                    <div class="panel-body">
                                                        <fieldset>
                                                            <div class="grid-image">
                                                                <img src="assets/img/admin.PNG" alt="admin">
                                                            </div>
                                                            <div class="grid-details">
                                                                <h4>Mary Jane</h4>
                                                                <a href="mailto:mary_jane4@gmail.com">mary_jane4@gmail.com</a>
                                                                <h4 class="text-color">Admin</h4>
                                                            </div>

                                                        </fieldset>

                                                        <div class="btn-wrap">
                                                            <a href="#">Follow</a>
                                                            <a href="#">Point:246</a>

                                                        </div>
                                                        <div class="panel-body-wrap">
                                                            <div class="follower-text pull-left">
                                                                <p>Followers:<span>62</span></p>
                                                            </div>
                                                            <div class="follower-text pull-right">
                                                                <p>Following:<span>215</span></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>

                                            <li data-name="Building" class="mix architecture mix_all"
                                                style="display: inline-block;  opacity: 1;">
                                                <div class="list-block employee">
                                                    <div class="panel-heading">
                                                        <div class="pull-right">
                                                            <a href="#"><i aria-hidden="true" class="fa fa-bell-o"></i></a>
                                                            <a href="#"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                                            <a href="#"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                                        </div>
                                                    </div>
                                                    <div class="panel-body">
                                                        <fieldset>
                                                            <div class="grid-image">
                                                                <img src="assets/img/employee.PNG" alt="employee">
                                                            </div>
                                                            <div class="grid-details">
                                                                <h4>Mike Shinoda</h4>
                                                                <a href="mailto:mike_shinoda69@linkinpark.com">mike_shinoda69@linkinpark.com</a>
                                                                <h4 class="text-color">Employee</h4>
                                                            </div>

                                                        </fieldset>

                                                        <div class="btn-wrap">
                                                            <a href="#">Follow</a>
                                                            <a href="#">Point:246</a>

                                                        </div>
                                                        <div class="panel-body-wrap">
                                                            <div class="follower-text pull-left">
                                                                <p>Followers:<span>62</span></p>
                                                            </div>
                                                            <div class="follower-text pull-right">
                                                                <p>Following:<span>215</span></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>


                                            <li data-name="Machiery" class="mix industrial mix_all"
                                                style="display: inline-block;  opacity: 1;">
                                                <div class="list-block super-user">
                                                    <div class="panel-heading">
                                                        <div class="pull-right">
                                                            <a href="#"><i aria-hidden="true" class="fa fa-bell-o"></i></a>
                                                            <a href="#"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                                            <a href="#"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                                        </div>

                                                    </div>
                                                    <div class="panel-body">
                                                        <fieldset>
                                                            <div class="grid-image">
                                                                <img src="assets/img/super-user.PNG" alt="super-user">
                                                            </div>
                                                            <div class="grid-details">
                                                                <h4>Jason Belmonte</h4>
                                                                <a href="mailto:jason_belmonte24@gmail.com">jason_belmonte24@gmail.com</a>
                                                                <h4>Super User</h4>
                                                            </div>

                                                        </fieldset>

                                                        <div class="btn-wrap">
                                                            <a href="#">Follow</a>
                                                            <a href="#">Point:246</a>

                                                        </div>
                                                        <div class="panel-body-wrap">
                                                            <div class="follower-text pull-left">
                                                                <p>Followers:<span>62</span></p>
                                                            </div>
                                                            <div class="follower-text pull-right">
                                                                <p>Following:<span>215</span></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>

                                            <li data-name="Fire Escape" class="mix architecture mix_all"
                                                style="display: inline-block;  opacity: 1;">
                                                <div class="list-block super-user">
                                                    <div class="panel-heading">
                                                        <div class="pull-right">
                                                            <a href="#"><i aria-hidden="true" class="fa fa-bell-o"></i></a>
                                                            <a href="#"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                                            <a href="#"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                                        </div>

                                                    </div>
                                                    <div class="panel-body">
                                                        <fieldset>
                                                            <div class="grid-image">
                                                                <img src="assets/img/super-user.PNG" alt="super-user">
                                                            </div>
                                                            <div class="grid-details">
                                                                <h4>Jason Belmonte</h4>
                                                                <a href="mailto:jason_belmonte24@gmail.com">jason_belmonte24@gmail.com</a>
                                                                <h4>Super User</h4>
                                                            </div>

                                                        </fieldset>

                                                        <div class="btn-wrap">
                                                            <a href="#">Follow</a>
                                                            <a href="#">Point:246</a>

                                                        </div>
                                                        <div class="panel-body-wrap">
                                                            <div class="follower-text pull-left">
                                                                <p>Followers:<span>62</span></p>
                                                            </div>
                                                            <div class="follower-text pull-right">
                                                                <p>Following:<span>215</span></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>

                                            <li data-name="Mossy Tree" class="mix nature mix_all"
                                                style="display: inline-block;  opacity: 1;">
                                                <div class="list-block super-user">
                                                    <div class="panel-heading">
                                                        <div class="pull-right">
                                                            <a href="#"><i aria-hidden="true" class="fa fa-bell-o"></i></a>
                                                            <a href="#"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                                            <a href="#"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                                        </div>
                                                    </div>
                                                    <div class="panel-body">
                                                        <fieldset>
                                                            <div class="grid-image">
                                                                <img src="assets/img/super-user.PNG" alt="super-user">
                                                            </div>
                                                            <div class="grid-details">
                                                                <h4>Jason Belmonte</h4>
                                                                <a href="mailto:jason_belmonte24@gmail.com">jason_belmonte24@gmail.com</a>
                                                                <h4>Super User</h4>
                                                            </div>

                                                        </fieldset>

                                                        <div class="btn-wrap">
                                                            <a href="#">Follow</a>
                                                            <a href="#">Point:246</a>

                                                        </div>
                                                        <div class="panel-body-wrap">
                                                            <div class="follower-text pull-left">
                                                                <p>Followers:<span>62</span></p>
                                                            </div>
                                                            <div class="follower-text pull-right">
                                                                <p>Following:<span>215</span></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>

                                            <li data-name="Demolition" class="mix industrial mix_all"
                                                style="display: inline-block;  opacity: 1;">
                                                <div class="list-block super-user">
                                                    <div class="panel-heading">
                                                        <div class="pull-right">
                                                            <i aria-hidden="true" class="fa fa-bell-o"></i>
                                                            <i class="fa fa-pencil" aria-hidden="true"></i>
                                                            <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                        </div>

                                                    </div>
                                                    <div class="panel-body">
                                                        <fieldset>
                                                            <div class="grid-image">
                                                                <img src="assets/img/super-user.PNG" alt="super-user">
                                                            </div>
                                                            <div class="grid-details">
                                                                <h4>Jason Belmonte</h4>
                                                                <a href="mailto:jason_belmonte24@gmail.com">jason_belmonte24@gmail.com</a>
                                                                <h4>Super User</h4>
                                                            </div>

                                                        </fieldset>

                                                        <div class="btn-wrap">
                                                            <a href="#">Follow</a>
                                                            <a href="#">Point:246</a>

                                                        </div>
                                                        <div class="panel-body-wrap">
                                                            <div class="follower-text pull-left">
                                                                <p>Followers:<span>62</span></p>
                                                            </div>
                                                            <div class="follower-text pull-right">
                                                                <p>Following:<span>215</span></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>

                                            <li data-name="Fountain" class="mix architecture mix_all"
                                                style="display: inline-block;  opacity: 1;">
                                                <div class="list-block super-user">
                                                    <div class="panel-heading">
                                                        <div class="pull-right">
                                                            <a href="#"><i aria-hidden="true" class="fa fa-bell-o"></i></a>
                                                            <a href="#"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                                            <a href="#"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                                        </div>
                                                    </div>
                                                    <div class="panel-body">
                                                        <fieldset>
                                                            <div class="grid-image">
                                                                <img src="assets/img/super-user.PNG" alt="super-user">
                                                            </div>
                                                            <div class="grid-details">
                                                                <h4>Jason Belmonte</h4>
                                                                <a href="mailto:jason_belmonte24@gmail.com">jason_belmonte24@gmail.com</a>
                                                                <h4>Super User</h4>
                                                            </div>

                                                        </fieldset>

                                                        <div class="btn-wrap">
                                                            <a href="#">Follow</a>
                                                            <a href="#">Point:246</a>

                                                        </div>
                                                        <div class="panel-body-wrap">
                                                            <div class="follower-text pull-left">
                                                                <p>Followers:<span>62</span></p>
                                                            </div>
                                                            <div class="follower-text pull-right">
                                                                <p>Following:<span>215</span></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>

                                            <li data-name="Rider" class="mix nature mix_all"
                                                style="display: inline-block;  opacity: 1;">
                                                <div class="list-block super-user">
                                                    <div class="panel-heading">
                                                        <div class="pull-right">
                                                            <a href="#"><i aria-hidden="true" class="fa fa-bell-o"></i></a>
                                                            <a href="#"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                                            <a href="#"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                                        </div>
                                                    </div>
                                                    <div class="panel-body">
                                                        <fieldset>
                                                            <div class="grid-image">
                                                                <img src="assets/img/super-user.PNG" alt="super-user">
                                                            </div>
                                                            <div class="grid-details">
                                                                <h4>Jason Belmonte</h4>
                                                                <a href="mailto:jason_belmonte24@gmail.com">jason_belmonte24@gmail.com</a>
                                                                <h4>Super User</h4>
                                                            </div>

                                                        </fieldset>

                                                        <div class="btn-wrap">
                                                            <a href="#">Follow</a>
                                                            <a href="#">Point:246</a>

                                                        </div>
                                                        <div class="panel-body-wrap">
                                                            <div class="follower-text pull-left">
                                                                <p>Followers:<span>62</span></p>
                                                            </div>
                                                            <div class="follower-text pull-right">
                                                                <p>Following:<span>215</span></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                        <div class="hide-table">
                                            <div tabindex="5000"
                                                 class="tab-pane active employee-tab" id="employee">
                                                <div class="container">

                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="panel panel-info" style="overflow-x:auto;">
                                                                <div class="panel-heading trophy">
                                                                    <h4 class="icon">Users List</h4>
                                                                    <div class="pull-right">
                                                                        <a href="settings.php"><img
                                                                                    src="assets/img/settings-icon.png"
                                                                                    alt="settings"></a>
                                                                    </div>
                                                                </div>
                                                                <div class="panel-body">
                                                                    <div class="row">
                                                                        <table class="table">
                                                                            <thead>
                                                                            <tr>
                                                                                <th>
                                                                                    <label class="check">User Name<input
                                                                                                type="checkbox">
                                                                                        <span class="checkmark"></span>
                                                                                    </label>
                                                                                </th>
                                                                                <th><label>User Email Id</label></th>
                                                                                <th><label>Role</label></th>
                                                                                <th><label>Position</label></th>
                                                                                <th><label>Following</label></th>
                                                                                <th><label>Followers</label></th>
                                                                                <th><label>Points</label></th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                            <tr>
                                                                                <td>
                                                                                    <label class="check">Jason
                                                                                        Durelo<input type="checkbox">
                                                                                        <span class="checkmark"></span>
                                                                                    </label>
                                                                                </td>
                                                                                <td><p>jason_durelo123@gamil.com</p>
                                                                                </td>
                                                                                <td><p>Employee</p></td>
                                                                                <td><p>Marketing Head</p></td>
                                                                                <td><p>235</p></td>
                                                                                <td><p>34</p></td>
                                                                                <td><p>240</p></td>

                                                                            </tr>
                                                                            </tbody>
                                                                            <tbody>
                                                                            <tr>
                                                                                <td>
                                                                                    <label class="check">Melllisa
                                                                                        McCarty<input type="checkbox">
                                                                                        <span class="checkmark"></span>
                                                                                    </label>
                                                                                </td>
                                                                                <td><p>melllisa_mc45m@gmail.com</p></td>
                                                                                <td><p><a href="#">Super User</a></p>
                                                                                </td>
                                                                                <td><p>Logistics Head</p></td>
                                                                                <td><p>235</p></td>
                                                                                <td><p>34</p></td>
                                                                                <td><p>240</p></td>

                                                                            </tr>
                                                                            </tbody>
                                                                            <tbody>
                                                                            <tr>
                                                                                <td>
                                                                                    <label class="check">Vennesa
                                                                                        Jay<input type="checkbox">
                                                                                        <span class="checkmark"></span>
                                                                                    </label>
                                                                                </td>
                                                                                <td><p>vennesa_jay@gmail.com</p></td>
                                                                                <td><p>Employee</p></td>
                                                                                <td><p>Sr. Finance Manager</p></td>
                                                                                <td><p>235</p></td>
                                                                                <td><p>34</p></td>
                                                                                <td><p>240</p></td>
                                                                            </tr>
                                                                            </tbody>
                                                                        </table>
                                                                        <div class="notice">
                                                                            <div class="action notice-left"><p>
                                                                                    Action</p></div>
                                                                            <div class="select notice-left">
                                                                                <select name="slct" id="slct">
                                                                                    <option>Edit</option>
                                                                                    <option value="Super User">Super
                                                                                        User
                                                                                    </option>
                                                                                    <option value="Employee">Employee
                                                                                    </option>
                                                                                    <option value="Admin">Admin</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            <div tabindex="5002"  class="tab-pane"
                                                 id="users">
                                                <div class="container">

                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="panel panel-info" style="overflow-x:auto;">
                                                                <div class="panel-heading trophy">
                                                                    <h4 class="icon">Users List</h4>
                                                                    <div class="pull-right">
                                                                        <a href="settings.php"><img
                                                                                    src="assets/img/settings-icon.png"
                                                                                    alt="settings"></a>
                                                                    </div>
                                                                </div>
                                                                <div class="panel-body">
                                                                    <div class="row">
                                                                        <table class="table">
                                                                            <thead>
                                                                            <tr>
                                                                                <th>
                                                                                    <label class="check">Group Manager
                                                                                        Details<input type="checkbox">
                                                                                        <span class="checkmark"></span>
                                                                                    </label>
                                                                                </th>
                                                                                <th><label>Role</label></th>
                                                                                <th><label>Manager Of</label></th>
                                                                                <th><label>Groups</label></th>
                                                                                <th><label>Following</label></th>
                                                                                <th><label>Followers</label></th>
                                                                                <th><label>Points</label></th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                            <tr>
                                                                                <td>
                                                                                    <label class="check">Jason
                                                                                        Durelo<input type="checkbox">
                                                                                        <span class="checkmark"></span>
                                                                                    </label>
                                                                                </td>
                                                                                <td><p>Business Development Manager</p>
                                                                                </td>
                                                                                <td><p>Design &amp; Tech, Marketing,
                                                                                        Business Development</p></td>
                                                                                <td><p>Maekting, Customer Service, Lead
                                                                                        Generation</p></td>
                                                                                <td><p>235</p></td>
                                                                                <td><p>34</p></td>
                                                                                <td><p>6854</p></td>

                                                                            </tr>
                                                                            </tbody>
                                                                            <tbody>
                                                                            <tr>
                                                                                <td>
                                                                                    <label class="check">Melllisa
                                                                                        McCarty<input type="checkbox">
                                                                                        <span class="checkmark"></span>
                                                                                    </label>
                                                                                </td>
                                                                                <td><p>Business Development Manager</p>
                                                                                </td>
                                                                                <td><p>Design &amp; Tech, Marketing,
                                                                                        Business Development</p></td>
                                                                                <td><p>Maekting, Customer Service, Lead
                                                                                        Generation</p></td>
                                                                                <td><p>235</p></td>
                                                                                <td><p>34</p></td>
                                                                                <td><p>6854</p></td>

                                                                            </tr>
                                                                            </tbody>
                                                                            <tbody>
                                                                            <tr>
                                                                                <td>
                                                                                    <label class="check">Vennesa
                                                                                        Jay<input type="checkbox">
                                                                                        <span class="checkmark"></span>
                                                                                    </label>
                                                                                </td>
                                                                                <td><p>Business Development Manager</p>
                                                                                </td>
                                                                                <td><p>Design &amp; Tech, Marketing,
                                                                                        Business Development</p></td>
                                                                                <td><p>Maekting, Customer Service, Lead
                                                                                        Generation</p></td>
                                                                                <td><p>235</p></td>
                                                                                <td><p>34</p></td>
                                                                                <td><p>6854</p></td>
                                                                            </tr>
                                                                            </tbody>
                                                                        </table>
                                                                        <div class="notice">
                                                                            <div class="action notice-left"><p>
                                                                                    Action</p></div>
                                                                            <div class="select notice-left">
                                                                                <select name="slct" id="slct">
                                                                                    <option>Edit</option>
                                                                                    <option value="Super User">Super
                                                                                        User
                                                                                    </option>
                                                                                    <option value="Employee">Employee
                                                                                    </option>
                                                                                    <option value="Admin">Admin</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            <div tabindex="5002" class="tab-pane" id="other-managers">
                                                <div class="container">

                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="panel panel-info" style="overflow-x:auto;">
                                                                <div class="panel-heading trophy">
                                                                    <h4 class="icon">Users List</h4>
                                                                    <div class="pull-right">
                                                                        <a href="settings.php"><img
                                                                                    src="assets/img/settings-icon.png"
                                                                                    alt="settings"></a>
                                                                    </div>
                                                                </div>
                                                                <div class="panel-body">
                                                                    <div class="row">
                                                                        <table class="table">
                                                                            <thead>
                                                                            <tr>
                                                                                <th>
                                                                                    <label class="check">User Name<input
                                                                                                type="checkbox">
                                                                                        <span class="checkmark"></span>
                                                                                    </label>
                                                                                </th>
                                                                                <th><label>User Email Id</label></th>
                                                                                <th><label>Role</label></th>
                                                                                <th><label>Position</label></th>
                                                                                <th><label>Following</label></th>
                                                                                <th><label>Followers</label></th>
                                                                                <th><label>Points</label></th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                            <tr>
                                                                                <td>
                                                                                    <label class="check">Jason
                                                                                        Durelo<input type="checkbox">
                                                                                        <span class="checkmark"></span>
                                                                                    </label>
                                                                                </td>
                                                                                <td><p>jason_durelo123@gamil.com</p>
                                                                                </td>
                                                                                <td><p><a href="#">Employee</a></p></td>
                                                                                <td><p>Marketing Head</p></td>
                                                                                <td><p>235</p></td>
                                                                                <td><p>34</p></td>
                                                                                <td><p>240</p></td>

                                                                            </tr>
                                                                            </tbody>
                                                                            <tbody>
                                                                            <tr>
                                                                                <td>
                                                                                    <label class="check">Melllisa
                                                                                        McCarty<input type="checkbox">
                                                                                        <span class="checkmark"></span>
                                                                                    </label>
                                                                                </td>
                                                                                <td><p>melllisa_mc45m@gmail.com</p></td>
                                                                                <td><p><a href="#">Super User</a></p>
                                                                                </td>
                                                                                <td><p>Logistics Head</p></td>
                                                                                <td><p>235</p></td>
                                                                                <td><p>34</p></td>
                                                                                <td><p>240</p></td>

                                                                            </tr>
                                                                            </tbody>
                                                                            <tbody>
                                                                            <tr>
                                                                                <td>
                                                                                    <label class="check">Vennesa
                                                                                        Jay<input type="checkbox">
                                                                                        <span class="checkmark"></span>
                                                                                    </label>
                                                                                </td>
                                                                                <td><p>vennesa_jay@gmail.com</p></td>
                                                                                <td><p><a href="#">Employee</a></p></td>
                                                                                <td><p>Sr. Finance Manager</p></td>
                                                                                <td><p>235</p></td>
                                                                                <td><p>34</p></td>
                                                                                <td><p>240</p></td>
                                                                            </tr>
                                                                            </tbody>
                                                                        </table>
                                                                        <div class="notice">
                                                                            <div class="action notice-left"><p>
                                                                                    Action</p></div>
                                                                            <div class="select notice-left">
                                                                                <select name="slct" id="slct">
                                                                                    <option>Edit</option>
                                                                                    <option value="Super User">Super
                                                                                        User
                                                                                    </option>
                                                                                    <option value="Employee">Employee
                                                                                    </option>
                                                                                    <option value="Admin">Admin</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!--col-lg-12-->
                </div>
            </div>
        </div>
    </div>
@endsection

