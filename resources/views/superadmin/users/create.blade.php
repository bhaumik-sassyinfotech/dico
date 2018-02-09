@extends('template.default')
<title>DICO - User</title>
@section('content')
    <div id="page-content" class="create-user create-user-popup">
        <div id='wrap'>
            <div id="page-heading">
                <ol class="breadcrumb">
                    <li><a href="{{ url('/home') }}">Dashboard</a></li>
                    <li><a href="{{ route('user.index') }}">User</a></li>
                    <li class="active">Create User</li>
                </ol>
                <h1 class="tp-bp-0">Create User</h1>
                <hr class="border-out-hr">

            </div>
            <div class="container">
                <div class="row">
                    <div id="create-user-from">
                        <form class="common-form" name="user_form" id="user_form" method="post"
                              action="{{route('user.store')}}">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label>Company<span>*</span></label>
                                <div class="select">
                                    <select id="company_id" name="company_id" class="form-control">
                                        <option value="">------ Select ------</option>
                                        @if( !empty($companies) )
                                            @foreach($companies as $company)
                                                <option value="{{$company->id}}">{{$company->company_name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="text-15">Full Name<span>*</span></label>
                                <input type="text" name="user_name" id="user_name" placeholder="Full Name"
                                       class="form-control required">
                            </div>

                            <div class="form-group">
                                <label class="text-15">Email Id<span>*</span></label>
                                <input type="text" name="user_email" id="user_email" placeholder="User Email" class="form-control" onkeyup="$('#emailerror').text('');" onblur="checkEmail(this.value,0)">
                                <label id="emailerror" class="error hidden" ></label>
                                <input id="for_me_emailerror" value="" type="hidden">            
                            </div>
                            <div class="form-group">
                                <label>Role:</label>
                                <div class="select">
                                    <select id="role_id" name="role_id" class="form-control">
                                        <option value="">------ Select ------</option>

                                        @if(!empty($roles))
                                            @foreach($roles as $role)
                                                <option value="{{$role->id}}">{{$role->role_name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Groups:</label>
                                <div class="select">
                                    <select name="user_groups[]" id="user_groups" class="form-control" multiple="multiple" style="width: 71%">
                                        <option disabled="disabled" value="">Select company first.</option>
                                    </select>
                                </div>
                                <div class="add-grp-wrap btn-wrap-div">
                                   <a type="submit" class="add-group" href="#myModal" data-toggle="modal">Create New Group</a>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="blank">
                                    <label class="check">
                                        <p>Active</p>
                                        If user is inactive, than user will not be able to login into the system.
                                        <input type="checkbox" name="is_active" id="is_active"  >
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="blank">
                                    <label class="check"><p>Suspend</p>If user is suspended, than user can login but will not be able to create a new post.
                                        <input type="checkbox" name="is_suspended" id="is_suspended" >
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="btn-wrap-div">
                                    <input type="submit" class="st-btn" value="Submit" />


                                    <a href="{{ url()->previous() }}" class="st-btn">Cancel</a>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div> <!-- container -->

    <!-- Modal -->
    <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade" style="display: none;">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button aria-hidden="true" data-dismiss="modal" class="desktop-close" type="button">×</button>
                                                <h4 class="modal-title">Create New Group</h4>
                                            </div>
                                            <div class="common-form">
                                                <form id="create_group_modal" method="POST">
                                                <div class="form-group">
                                                    <label class="text-15">Group Name:*</label>
                                                    <input class="required" type="text" name="grp_name" id="grp_name" placeholder="Management">
                                                </div>
                                                <div class="form-group">
                                                    <p class="error" id="company-warn" style="display: none;">Please select the company to which this group belongs.</p>
                                                </div>

                                                <div class="form-group">
                                                    <label class="text-15">Description:</label>
                                                    <textarea name="grp_desc" id="grp_desc" type="text" placeholder="Lorem ipsum is a dummy text in the all typesetting industry"></textarea>
                                                </div>
                                                <div class="form-group">
                                                   <div class="btn-wrap-div">
                                                      <input class="st-btn" type="submit" value="Create">
                                                      <input type="reset" value="Cancel" class="st-btn" data-dismiss="modal">
                                                  </div>
                                              </div>
                                              </form>
                                          </div>
                                      </div><!-- /.modal-content -->
                                  </div><!-- /.modal-dialog -->
                              </div>

    <!-- Modal End -->
@stop
@section('javascript')
    <script type="text/javascript">
    </script>
@endsection
