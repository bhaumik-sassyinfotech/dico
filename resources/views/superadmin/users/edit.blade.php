@extends('template.default')
<title>DICO - User</title>
@section('content')
    <div id="page-content" class="create-user create-user-popup">
        <div id='wrap'>
            <div id="page-heading">
                <ol class="breadcrumb">
                    <li><a href="{{ url('/home') }}">Dashboard</a></li>
                    <li><a href="{{ route('user.index') }}">User</a></li>
                    <li class="active">Update User</li>
                </ol>
                <h1 class="tp-bp-0">Update User</h1>
                <hr class="border-out-hr">
            
            </div>
            <div class="container">
                <div class="row">
                    @include('template.notification')
                    <div id="create-user-from">
                        <form class="common-form" name="user_form" id="user_form" method="POST" enctype= 'multipart/form-data'
                              action="{{route('user.update',[$user->id])}}">
                            {{ method_field('PUT') }}
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label>Company<span>*</span></label>
                                <div class="select">
                                    <select id="company_id" name="company_id" class="form-control" disabled="disabled">
                                        <option value="">------ Select ------</option>
                                        @if( !empty($companies) )
                                            @foreach($companies as $company)
                                                <option value="{{$company->id}}" {{ ( $company->id == $user->company_id )  ? "selected" : ' '  }}>{{$company->company_name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="text-15">Full Name<span>*</span></label>
                                <input type="text" name="user_name" id="user_name" value="{{$user->name}}" placeholder="Full Name"
                                       class="form-control required">
                            </div>
                            
                            <div class="form-group">
                                <label class="text-15">Email Id<span>*</span></label>
                                <input type="text" name="user_email" id="user_email" value="{{$user->email}}" placeholder="User Email"
                                       class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Role:</label>
                                <div class="select">
                                    <select id="role_id" name="role_id" class="form-control">
                                        <option value="">------ Select ------</option>
                                        
                                        @if(!empty($roles))
                                            @foreach($roles as $role)
                                                <option value="{{$role->id}}" {{ ( $user->role_id == $role->id ) ? 'selected ': ' ' }}>{{$role->role_name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Groups:</label>
                                <select name="user_groups[]" id="user_groups" class="form-control select"
                                        multiple="multiple" style="width: 71%">
                                    @if(count($groups) > 0)
                                        @foreach($groups as $group)
                                            <option value="{{ $group->id }}" {{ ( in_array($group->id, $user_group_ids) ) ? 'selected ': ' ' }}>{{$group->group_name}}</option>
                                        @endforeach
                                    @endif
                                </select>
    
                            </div>
                            <div class="form-group">
                                <div class="blank">
                                    <label class="check">
                                        <p>Active</p>
                                        If user is inactive, than user will not be able to login into the system.
                                        <input type="checkbox" name="is_active" id="is_active"  {{ ( $user->is_active == 1 ) ? "checked" : ' ' }} >
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="blank">
                                    <label class="check"><p>Suspend</p>If user is suspended, than user can login but will not be able to create a new post.
                                        <input type="checkbox" name="is_suspended" id="is_suspended" {{ ( $user->is_suspended == 1 ) ? "checked" : ' ' }} >
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="btn-wrap-div">
                                    <input type="submit" class="st-btn" value="Submit" />
                                    
                                    <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1"
                                         id="myModal" class="modal fade" style="display: none;">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button aria-hidden="true" data-dismiss="modal"
                                                            class="desktop-close" type="button">×
                                                    </button>
                                                    <div class="create-user-wrap">
                                                        <div class="create-box S-letter">
                                                            <a href="super-user.php">
                                                                <h1>S</h1>
                                                                <p>Super User</p>
                                                            </a>
                                                        </div>
                                                        <div class="create-box A-letter">
                                                            <a href="admin-user.php">
                                                                <h1>A</h1>
                                                                <p>Admin</p>
                                                            </a>
                                                        </div>
                                                        <div class="create-box E-letter">
                                                            <a href="employee-user.php">
                                                                <h1>E</h1>
                                                                <p>Employee</p>
                                                            </a>
                                                        </div>
                                                    
                                                    </div>
                                                </div>
                                            
                                            </div><!-- /.modal-content -->
                                        </div><!-- /.modal-dialog -->
                                    </div>
                                    <a href="{{ url()->previous() }}" class="st-btn">Cancel</a>
                                </div>
                            </div>
                        
                        </form>
                    
                    
                    </div>
                </div>
            </div>
        
        </div>
    </div> <!-- container -->
@stop
@section('javascript')
    <script type="text/javascript">
    </script>
@endsection