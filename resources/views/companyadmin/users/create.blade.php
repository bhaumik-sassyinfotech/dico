@extends('template.default')
<title>@lang("label.DICOUser")</title>
@section('content')
    <div id="page-content" class="create-user create-user-popup">
        <div id='wrap'>
            <div id="page-heading">
                <ol class="breadcrumb">
                    <li><a href="{{ route('/home') }}">@lang("label.adDashboard")</a></li>
                    <li><a href="{{ route('user.index') }}">@lang("label.adUser")</a></li>
                    <li class="active">@lang("label.CreateUser")</li>
                </ol>
                <h1 class="tp-bp-0">@lang("label.CreateUser")</h1>
                <hr class="border-out-hr">

            </div>
            <div class="container">
                <div class="row">
                    <div id="create-user-from">
                        <form class="common-form" name="user_form" id="user_form" method="post" action="{{route('user.store')}}">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label>@lang("label.ad")<span>*</span></label>
                                    <select id="company_id" name="company_id" class="form-control select">
                                        <option value="">------ @lang("label.Select") ------</option>
                                        @if( !empty($companies) )
                                            @foreach($companies as $company)
                                                <option value="{{$company->id}}">{{$company->company_name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                            </div>

                            <div class="form-group">
                                <label class="text-15">@lang("label.Full Name")<span>*</span></label>
                                <input type="text" name="user_name" id="user_name" placeholder="@lang('label.Full Name')"
                                       class="form-control required">
                            </div>

                            <div class="form-group">
                                <label class="text-15">@lang("label.Email_Id")<span>*</span></label>
                                <input type="text" name="user_email" id="user_email" placeholder="@lang('label.UserEmail')" class="form-control" onkeyup="$('#emailerror').text('');" onblur="checkEmail(this.value,0)">
                                <label id="emailerror" class="error hidden" ></label>
                                <input id="for_me_emailerror" value="" type="hidden">            
                            </div>
                            <?php /*
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
                            </div>*/?>
                            <input type="hidden" name="role_id" id="role_id">
                            <div class="form-group">
                                <label>@lang("label.Groups")</label>
                                <div class="select">
                                    <select name="user_groups[]" id="user_groups" class="form-control" multiple="multiple">
                                        <option disabled="disabled" value="">@lang("label.Selectcompanyfirst")</option>
                                    </select>
                                </div>
                                <div class="add-grp-wrap btn-wrap-div">
                                   <a type="submit" class="add-group" href="#myModal" data-toggle="modal">@lang("label.CreateNewGroup")</a>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="blank">
                                    <label class="check">
                                        <p>@lang("label.Active")</p>
                                        @lang("label.InactiveNote")
                                        <input type="checkbox" name="is_active" id="is_active">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="blank">
                                    <label class="check"><p>@lang("label.Suspend")</p>@lang("label.suspendedNote")
                                        <input type="checkbox" name="is_suspended" id="is_suspended">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div> 
                            <div class="form-group">
                                <div class="btn-wrap-div">
                                    <!-- <input type="submit" class="st-btn" value="Submit" />-->
                                    <a type="submit" class="st-btn" href="#createModal" data-toggle="modal">@lang("label.Create")</a>
                                    <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="createModal" class="modal fade" style="display: none;">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button aria-hidden="true" data-dismiss="modal" class="desktop-close" type="button">×</button>
                                                    <div class="create-user-wrap">
                                                        <div class="create-box A-letter">
                                                            <a href="javascript:void(0)" onclick="selectRole(2)">
                                                                <h1>@lang("label.A")</h1>
                                                                <p>@lang("label.Admin")</p>
                                                            </a>    
                                                        </div>
                                                        <div class="create-box E-letter">
                                                            <a href="javascript:void(0)" onclick="selectRole(3)">
                                                                <h1>@lang("label.E")</h1>
                                                                <p>@lang("label.Employee")</p>
                                                            </a>    
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><!-- /.modal-content -->
                                        </div><!-- /.modal-dialog -->
                                                                 </div>
                                    <a href="{{ url()->previous() }}" class="st-btn">@lang("label.Cancel")</a>
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
                                                <h4 class="modal-title">@lang("label.CreateNewGroup")</h4>
                                            </div>
                                            <div class="common-form">
                                                <form id="create_group_modal" method="POST">
                                                <div class="form-group">
                                                    <label class="text-15">@lang("label.GroupName"):*</label>
                                                    <input class="required" type="text" name="grp_name" id="grp_name" placeholder="@lang('label.Management')">
                                                </div>
                                                <div class="form-group">
                                                    <p class="error" id="company-warn" style="display: none;">Please select the company to which this group belongs.</p>
                                                </div>

                                                <div class="form-group">
                                                    <label class="text-15">@lang("label.adDescription"):</label>
                                                    <textarea name="grp_desc" id="grp_desc" type="text" placeholder="@lang('label.DescriptionText')"></textarea>
                                                </div>
                                                <div class="form-group">
                                                   <div class="btn-wrap-div">
                                                      <input class="st-btn" type="submit" value="@lang('label.Create')">
                                                      <input type="reset" value="@lang('label.Cancel')" class="st-btn" data-dismiss="modal">
                                                  </div>
                                              </div>
                                              </form>
                                          </div>
                                      </div><!-- /.modal-content -->
                                  </div><!-- /.modal-dialog -->
                              </div>

    <!-- Modal End -->
@endsection
@push('javascripts')
    <script type="text/javascript">
        function selectRole(roleid) {
            $('#role_id').val(roleid);
            $('#createModal').fadeOut();
            $('body').removeClass('modal-open');
            if($('#user_form').valid() == 1) {
                $('form#user_form').submit();
            } else {
                return false;
            }
        }
    </script>
@endpush
