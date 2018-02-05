/*Dropdown while adding security question when user is logging in for the first time*/
var previous = previous_val = 0;
$(".sec_question").on('focus', function () {
    // Store the current value on focus and on change
    previous = this.value;
}).change(function () {
    // Do something with the previous value after the change
    //alert(previous);
    var previous_val = previous; //alert(p);
    var selected = $(this).val();
    var opts = $(this)[0].options;
    var array = $.map(opts, function (elem) {
        return (elem.value || elem.text);
    });
    // console.log(array);
    $('.sec_question').each(function () {
        var v = $(this).val();
        if (previous_val != '') {
            // $('option[value="' + previoues_val + '"]').removeAttr('disabled');
            $('option[value="' + previous_val + '"]').show();
        }
        // $('option[value="' + selected + '"]').attr('disabled', 'disabled');
        $('option[value="' + selected + '"]').hide();
        // $('option[value=""]').removeAttr('disabled');
        $('option[value=""]').show();
    });
    // Make sure the previous value is updated
    previous = this.value;
});


$(document).ready(function () {

    $('.checkAll').change(function(){
        
        var ischecked= $('.checkAllBox').is(':checked');    

        if(ischecked)
        {//checked
            $('input:checkbox').not('.checkAllBox').prop('checked',true);
        } else 
        {//unchecked
            $('input:checkbox').not('.checkAllBox').prop('checked',false);
        }
    });
    $('#users_listing').select2({
        placeholder: "Select Company first",
    });
    $("#company_listing,#company_users,#group_owner").select2();
    $("#user_groups").select2();
    $("#group_listing").select2();

    /*show users in box while creating new meeting*/
    //$('#employees_listing').on('select2:select',function (e) {
    $('#add_employee').click(function(e) {
        //var data = e.params.data;
        var userId = $('#employees_listing').val();
        var dataString = {_token:CSRF_TOKEN,user_id: userId};
        e.preventDefault();
        // console.log(SITE_URL+'/getUserProfile');
         console.log(dataString);
        var ajaxURL = '/dico/getUserProfile';
        $.ajax({
            url: ajaxURL,
            data:dataString,
            type:"POST",
            success: function (response) {
                //console.log(response);
                if(response.status == '0')
                {
                    swal("Error","Member already added.","error");
                    //swal("Error","Some Error occured. Please try again later.","error");
                } else if(response.status == '1')
                {
                    var profile_pic = '';
                    if(response.data.profile_image != "") {
                        profile_pic = ASSETS_PATH+PROFILE_PATH + response.data.profile_image;
                    } else {
                        profile_pic = ASSETS_PATH+DEFAULT_PROFILE_IMAGE;
                    }
                    var userid = response.data.id;
                    var name = response.data.name;
                    var email = response.data.email;
                    var html = '';
                    html += '<div class="member-wrap" id="user_'+userid+'">';
                    html += '<div class="member-img">';
                    html += '<img src="'+profile_pic+'"/></div>';
                    html += '<div class="member-details">';
                    html += '<h3 class="text-12">'+name+'</h3>';
                    html += '<a href="mailto:'+email+'">'+email+'</a></div>';
                    html += '<a href="javascript:void(0)" onclick="removeMember('+userid+')" class="close-button-small"></a></div>';
                    $("#meeting_users_list").append(html);
                    $('#employees_listing option[value="'+userid+'"]').attr("disabled", true);
                }
            }
        });
    });
});
$("#createUserGroup").validate({
    submitHandler: function (form) {
        $("#save").prop('disabled', true);
        form.submit();
    }
});
/*Ajax Call to fetch user of the company selected*/
$("#company_listing").change(function () {
    var that = $(this);
    var dataString = {_token: CSRF_TOKEN, company_id: that.val()};
    if (that.val() != "")
    {
        $.ajax({
            url: 'companyUsers',
            data: dataString,
            method: "POST",
            success: function (response) {
                $("#company-warn").css('display','none');
                $("#users_listing,#group_owner").empty().append($("<option></option>").val("").html("Select user"));
                var userData = response.data;
                var role = '';
                $.each(userData, function (key, val) {
                    if (val.role_id == '2')
                        role = 'Manager';
                    else if (val.role_id == '3')
                        role = 'Employee';
                    $("#users_listing,#group_owner").append("<option value='" + val.id + "'>" + val.name + "(" + role + ")" + "</option>");
                });
            }
        });
    }
});
$("#group_owner").change(function () {
    var userDropDown = $("#users_listing");
    var group_owner = $("#group_owner").val();
    var company_id = $("#company_listing").val();
    var dataString = {_token: CSRF_TOKEN, company_id: company_id , group_owner: group_owner};
    // console.log([group_owner, company_id, dataString]);
    // return false;
    $.ajax({
        method: "POST",
        url: 'companyUsers',
        data: dataString,
        async: false,
        success: function (response) {
            userDropDown.empty();
            var status = response.success;
            if (status == 1) {
                var groups = response.data;
                $.each(groups, function (key, val) {
                    var role = '';
                    if (val.role_id == '2')
                        role = 'Manager';
                    else if (val.role_id == '3')
                        role = 'Employee';
                    userDropDown.append("<option value='" + val.id + "'>" + val.name + "(" + role + ")" + "</option>");
                    // userDropDown.append($("<option></option>").val(val.id).html(val.group_name));
                });
            } else if (status == 0) {
                console.error(response.msg);
            } else {
                console.log("some other error occured");
            }
        }
    })

});
/*group listing >> View -> admin\group\index */
var groupTable = $('#group_table').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: SITE_URL + '/group/list',
        data: function (d) {
            d.search_text = $("#search_query").val();
        }
    },
    searching: false,
    columns: [
        {data: 'group_name',sorting: false, orderable: false},
        {data: 'description'},
        {data: 'group_posts_count'},
        {data: 'group_users_count'},
        {data: 'actions', sorting: false, orderable: false}
    ]
});

//  $(document).on('click',".searchBtn" , function(){
//     // groupTable.draw(); 
//     // search();
// });                                             

var groupEditTable = $("#group_users_edit_table").DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: SITE_URL + '/group/editUsers',
        data: function (d) {
            d.group_id   = $("#group_id").val();
            d.company_id = $("#company_id").val();
        }
    },
    searching: false,
    columns: [
        {data: 'detail'},
        {data: 'following'},
        {data: 'followers'},
        {data: 'points'},
        {data: 'admin', sorting: false, orderable: false}
    ]
});
/*promote to admin ajax call for updating user in a group to admin */
$(document).on('click', '.promoteToAdmin', function (event) {
    event.preventDefault();
    var that = $(this);

    var group_user_id = that.data('groupUserId');
    var companyId = $("#company_id").val();
    var groupId = $("#group_id").val();
    var dataString = {
        _token: CSRF_TOKEN,
        groupUserId: group_user_id,
        company_id: companyId,
        group_id: groupId,
        makeAdmin: 1
    };
    swal({
            title: "Are you sure?",
            text: "User will be promoted to admin of this group!",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes",
            closeOnConfirm: false
        },
        function () {
            $.ajax({
                url: SITE_URL + '/group/editUsers',
                method: 'POST',
                data: dataString,
                success: function (response) {

                    var status = response.status;
                    swal("Success", response.msg, "success");
                    if (status == 1) {
                        groupEditTable.draw();
                    }
                    companyUsers();
                }
            });
        });
});

$(document).on('click', '.demoteToUser', function (event) {
    event.preventDefault();
    var that = $(this);
    swal({
            title: "Are you sure?",
            text: "Admin will be relegated to a normal user of this group!",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-success",
            confirmButtonText: "Yes",
            closeOnConfirm: false
        },
        function () {
            var group_user_id = that.data('groupUserId');
            var groupId = $("#group_id").val();
            var companyId = $("#company_id").val();
            var dataString = {
                _token: CSRF_TOKEN,
                groupUserId: group_user_id,
                company_id: companyId,
                group_id: groupId,
                removeAsAdmin: 1
            };

            $.ajax({
                url: SITE_URL + '/group/editUsers',
                method: 'POST',
                data: dataString,
                success: function (response) {

                    var status = response.status;
                    swal("Success", response.msg, "success");
                    if (status == 1) {
                        groupEditTable.draw();
                    }
                    companyUsers();
                }
            });
        });
});

$(document).on('click', '.removeUser', function (event) {
    var that = $(this);
    event.preventDefault();
    swal({
            title: "Are you sure?",
            text: "User will be removed from this group!",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes",
            closeOnConfirm: false
        },
        function () {
            var group_user_id = that.data('groupUserId');
            // console.log(group_user_id);
            var groupId = $("#group_id").val();
            var companyId = $("#company_id").val();
            var dataString = {
                _token: CSRF_TOKEN,
                groupUserId: group_user_id,
                company_id: companyId,
                group_id: groupId,
                removeFromGroup: 1
            };
            $.ajax({
                url: SITE_URL + '/group/editUsers',
                method: 'POST',
                data: dataString,
                success: function (response) {

                    var status = response.status;

                    swal("Success", response.msg, "success");
                    if (status == 1) {
                        groupEditTable.draw();
                    }
                    companyUsers();
                }
            });


        });
});
$("#group_users_edit_form").validate({
    submitHandler: function (form) {
        $("#save").prop('disabled', true);
        form.submit();
    }
});
$(document).on('click','.addUserToGroup',function()
{
    var that = $(this);
    event.preventDefault();

    var user_mail = $("#user_email").val();

    swal({
        title: "Are you sure?",
        // text: "You want to "+dataString.idea_status.charAt(0).toUpperCase() + dataString.idea_status.slice(1)+" the idea?",
        text: "Add user by email address.",
        type: "input",
        inputPlaceholder: "Email address",
        showCancelButton: true,
        closeOnConfirm: false,
        showLoaderOnConfirm: true
    }, function (inputValue) {
        if (inputValue === false) return false;
        if (inputValue === "" || $.trim(inputValue) === "")
        {
            swal.showInputError("Please enter email address");
            return false
        }
        var pattern = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
        var reg = pattern.test(inputValue);
        if(!reg)
        {
            swal.showInputError("Please enter valid email address");
            return false
        }

        var groupId   = that.data('groupId');
        var companyId = that.data('companyId');
        var dataString = {
            _token: CSRF_TOKEN,
            user: inputValue,
            company_id: companyId,
            group_id: groupId
        };

        $.ajax({
            url: SITE_URL + '/group/addUserByEmailAddress',
            method: "POST",
            data: dataString,
            success: function (response) {
                if(response.status == 0)
                    swal("Error", response.msg, "error");
                else if(response.status == 1)
                    swal("Success", response.msg, "success");
            }
        });

    });
});
$("#add_user").click(function (event) {
    //
    event.preventDefault();
    var users = $("#company_users").val();
    if ($.trim(users) !== "") {
        var companyId = $("#company_id").val();
        var groupId = $("#group_id").val();
        var dataString = {
            _token: CSRF_TOKEN,
            addGroupUsers: 1,
            users_list: users,
            company_id: companyId,
            group_id: groupId
        };
        $.ajax({
            method: "POST",
            url: SITE_URL + '/group/editUsers',
            data: dataString,
            success: function (response) {
                var status = response.status;
                if (status == 1) {
                    swal("Success", response.msg, "success");
                }
                companyUsers();
                groupEditTable.draw();
            }

        })
    } else {
        swal("Error!", "Please select users.", "error");
    }
});


function companyUsers() {
    var companyId = $("#company_id").val();
    var groupId = $("#group_id").val();
    var dataString = {_token: CSRF_TOKEN, company_id: companyId, group_id: groupId, getGroupUsers: 1};
    $.ajax({
        url: SITE_URL + '/group/editUsers',
        method: 'POST',
        data: dataString,
        success: function (response) {
            var userSelect = $("#company_users");
            userSelect.empty();
            var users = response.data;
            var status = response.status;
            if (status === 1) {
                $.each(users, function (key, value) {
                    userSelect.append($("<option></option>").html(value.name).val(value.id));
                });
            }
        }
    });
}

/*show group of the company selected*/
$("#company_id").change(function () {
    var that = $(this);
    var groupDropDown = $("#user_groups");
    var company_id = that.val();
    var dataString = {_token: CSRF_TOKEN, companyId: company_id};
    $.ajax({
        method: "POST",
        url: SITE_URL + '/user/getCompanyGroups',
        data: dataString,
        success: function (response) {
            groupDropDown.empty();
            var status = response.status;
            if (status == 1) {
                var groups = response.data;

                $.each(groups, function (key, val) {
                    groupDropDown.append($("<option></option>").val(val.id).html(val.group_name));
                });
            } else if (status == 0) {
                console.error(response.msg);
            } else {
                console.log("some other error occured");
            }
        }
    })

});

/*change status of an idea */
$('.ideaStatus').click(function (ev) {
    var that = $(this);
    ev.preventDefault();
    var postId = $("#post_id").val();
    var dataString = {_token: CSRF_TOKEN, post_id: postId, idea_status: null};
    var btnClicked = that.data('postStatus');

    if (btnClicked === 'approve') {
        dataString.idea_status = 'approve';
    } else if (btnClicked === 'deny') {
        dataString.idea_status = 'deny';
    } else if (btnClicked === 'amend') {
        dataString.idea_status = 'amend';
    }

    if (dataString.idea_status !== null) {
        swal({
            title: "Are you sure?",
            // text: "You want to "+dataString.idea_status.charAt(0).toUpperCase() + dataString.idea_status.slice(1)+" the idea?",
            text: "This will " + dataString.idea_status + " the idea.",
            type: "input",
            inputPlaceholder: "Reason",
            showCancelButton: true,
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        }, function (inputValue) {
            if (inputValue === false) return false;
            if ( inputValue === "" || $.trim(inputValue ) === "")
            {
                swal.showInputError("Please enter a valid reason for your decision!");
                return false
            }
            dataString.idea_reason = inputValue;
            $.ajax({
                url: SITE_URL + '/post/change-status',
                method: "POST",
                data: dataString,
                success: function (response) {
                    swal("Success", response.msg, "success");
                }
            });

        });
    } else
        alert("null");

});

$("#createMeeting").validate({
    rules: {
        'meeting_title': {
            required: true,
            maxlength: 255
        },
        'privacy': {
            required: true,
            minlength: 1
        }
    },
    errorPlacement: function (error, element) {
        if (element.attr("name") == "privacy") {
            error.appendTo("#error_privacy");
        } else {
            error.insertAfter(element);
        }
    },
    submitHandler: function (form) {
        $("#save").prop('disabled', true);
        form.submit();
    }
});
$("#meeting_table").DataTable({
    processing: true,
    serverSide: true,
    searching: false,
    ajax: {
        url: SITE_URL + '/meeting/list',
        data: function (d) {
            d.group_id = $("#group_id").val();
            d.company_id = $("#company_id").val();
        }
    },
    columns: [
        {data: 'rownum'},
        {data: 'meeting_title'},
        {data: 'meeting_description'},
        {data: 'privacy'},
        {data: 'meeting_users_count'},
        {data: 'actions', sorting: false, orderable: false},
    ]
});

$("#login_form").validate({
    // errorElement: "div",
    // errorClass: "error1 error",
    rules:{
        email: {
            required: true,
            email: true,
        },
        password:{
            required: true,
        }
    },
    errorPlacement: function(error, element) {

        if(element.attr("name") == "email")
            error.appendTo('#email_error');
        if(element.attr("name") == "password")
            error.appendTo('#password_error');

    },
    submitHandler: function (form) {
        $(".loginBtn").prop('disabled',true);
        form.submit();
    }
});

// $('#delete_post_form').submit();

$('.delete_post_btn').click(function () {
    swal({
            title: "Are you sure?",
            text: "This post will be deleted.",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes",
            closeOnConfirm: false
        },
        function () {
            $.ajax({
                url: SITE_URL + '/group/editUsers',
                method: 'POST',
                data: dataString,
                success: function (response) {

                    var status = response.status;
                    swal("Success", response.msg, "success");
                    if (status == 1) {
                        groupEditTable.draw();
                    }
                    companyUsers();
                }
            });
        });
});

$("#finalize_meeting_form").validate({
    rules:{
        'meeting_comment': "required",
        'meeting_summary': "required"
    }, submitHandler: function (form) {
        $(".saveBtn").prop('disabled',true);
        // return false;
        form.submit();
    }
});

$(document).on('click','.leaveMeeting',function () {
    var meeting_id = $("#meeting_id").val();
    var dataString = {_token: CSRF_TOKEN , meeting_id:meeting_id };
    $.ajax({
        url:SITE_URL+'/meeting/leaveMeeting',
        data: dataString,
        method: "POST",
        success: function (response)
        {
            var status = response.status;
            var msg = response.msg;
            var data = response.data;
            if (status == '1') {
                $('.leaveMeeting').remove();
                alert(msg);
                location.reload();
            } else {
                swal("Error!", msg, "error");
            }
        }
    });
});


// $(document).on('click','.superUser', function(){
//     console.log($(this).text());
//     console.log("============");
// });
function superUserGrid(type)
{
    // console.log(type);
    // console.log('===============');
    $("#offset").attr('data-tab',type);
    $("#offset").val(0);
    $('#load_post').slideDown();
    var url = new_url = '';
    if(type == 1)
        url = '/user/employeeGrid';
    else if(type == 2)
        url = '/user/adminGrid';
    else if(type == 3)
        url = '/user/otherManagersGrid';
    var search_text = $.trim($("#search_query").val());
    // console.log(url);
    new_url = SITE_URL+url;
    var dataString = { _token : CSRF_TOKEN , search : search_text };
    $("#display-grid").empty();
    $.ajax({
        url:new_url,
        data: dataString, 
        method:"POST",
        async: true,
        success: function(response)
        {
            
            // $("#display-grid").empty();
            // alert("succ");
            $("#display-grid").html(response.html);
            setTimeout(function()
            {
                if( $('.userList').length == response.count )
                {
                    $('#load_post').hide();
                    $(".all_viewmore").hide();
                } else
                {
                    $(".all_viewmore").not(':last').remove();
                }
            },10);
        }
    }); 
}

$(document).on('click',"#search_btn",function(){
    var search_text = $.trim($("#search_query").val());
    $("#offset").val(0);
    var type = $("#offset").attr('data-tab');
    var url = new_url = '';
    if(type == 1)
    {
        url = '/user/employeeGrid';
    }
    else if(type == 2)
    {
        url = '/user/adminGrid';
    }
    else if(type == 3)
    {
        url = '/user/otherManagersGrid';
    }
    var search_text = $.trim($("#search_query").val());
    
    new_url = SITE_URL+url;
    var dataString = { _token : CSRF_TOKEN , search : search_text,offset:0 };
    console.log(dataString);
    $("#display-grid").empty();
    $.ajax({
        url:new_url,
        data: dataString, 
        method:"POST",
        success: function(response)
        {
            companyuserTable.draw(); // custom.js
            employeeTable_superadmin.draw(); // for super-user
            otherManagerTable_superadmin.draw(); // for super-user
            companyManagerTable_superadmin.draw(); // for super-user

            $("#display-grid").html(response.html);
            setTimeout(function()
            {
                if( $('.userList').length == response.count )
                {
                    $('#load_post').hide();
                    $(".all_viewmore").hide();
                } else
                {
                    $(".all_viewmore").not(':last').remove();
                }
            } , 10);
        }
    }); 
});

/*users listing for super-user role */
/*employee listing*/
var employeeTable_superadmin = $("#emp-table").DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: SITE_URL + '/user/employeeList',
        data: function (d) {
            d.role_id      = $("#role_id").val();
            d.search_query = $("#search_query").val();
        }
    },
    searching: false,
    columns: [
        {data: 'name', sorting: false, orderable: false},
        {data: 'email'},
        {data: 'role'},
        {data: 'followers_count'},
        {data: 'following_count'},
        {data: 'points'},
        // {data: 'admin', sorting: false, orderable: false}
    ]
});

var otherManagerTable_superadmin = $("#other-managers-table").DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: SITE_URL + '/user/otherManagersList',
        data: function (d) {
            // d.role_id      = $("#role_id").val();
            d.search_query = $("#search_query").val();
        }
    },
    searching: false,
    columns: [
        {data: 'name', sorting: false, orderable: false},
        {data: 'email'},
        {data: 'role'},
        {data: 'position'},
        {data: 'following_count'},
        {data: 'followers_count'},
        {data: 'points'},
        // {data: 'admin', sorting: false, orderable: false}
    ]
});
var companyManagerTable_superadmin = $("#company-manager-table").DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: SITE_URL + '/user/adminList',
        data: function (d) {
            // d.role_id      = $("#role_id").val();
            d.search_query = $.trim($("#search_query").val());
        }
    },
    searching: false,
    columns: [
        {data: 'name', sorting: false, orderable: false},
        {data: 'role'},
        {data: 'group_admins'},
        {data: 'following_count'},
        {data: 'followers_count'},
        {data: 'points'},
        // {data: 'admin', sorting: false, orderable: false}
    ]
});

// user-groups search on view profile page
$(document).on('click',"#search_group_btn",function(){
    var search_text = $.trim($("#search_group_text").val());
    var uid = $("#user_id").val();
    dataString = {_token: CSRF_TOKEN,user_id: uid ,search:search_text};
    $("#view_user_groups").empty();
    $.ajax({
        url: SITE_URL+"/group/search",
        data: dataString,
        method:"POST",
        success: function(response){
            $("#view_user_groups").append(response.html).owlCarousel('destroy').owlCarousel();
            // var owl = $('.owl-carousel');
        }
    });
});

$(document).on('click',"#search_post_btn",function(){
    var search_text = $.trim($("#search_post_text").val());
    var uid = $("#user_id").val();
    dataString = {_token: CSRF_TOKEN,user_id: uid ,search:search_text};
    $("#view_posts").empty();
    $.ajax({
        url: SITE_URL+"/searchPost",
        data: dataString,
        method:"POST",
        success: function(response){
            $("#view_posts").append(response.html).owlCarousel('destroy').owlCarousel();
            // var owl = $('.owl-carousel');
        }
    });
});



function groupGrid(type)
{
    $("#offset").attr('data-tab',type);
    $("#offset").val(0);
    $('#load_post').slideDown();
    var url = new_url = '';
    if(type == 1)
        url = '/';
    else if(type == 2)
        url = '/user/mygroups';

    var search_text = $.trim($("#search_query").val());
    // console.log(url);
    new_url = SITE_URL+url;
    var dataString = { _token : CSRF_TOKEN , offset: offset_val , search : search_text };
    $("#display-grid").empty();
    $.ajax({
        url:new_url,
        data: dataString, 
        method:"POST",
        async: true,
        success: function(response)
        {
            $("#display-grid").html(response.html);
            setTimeout(function()
            {
                if( $('.userList').length == response.count )
                {
                    $('#load_post').hide();
                    $(".all_viewmore").hide();
                } else
                {
                    $(".all_viewmore").not(':last').remove();
                }
            },10);
        }
    });    
}

// function notice_submit(){
//     $(document).on('click','.multiple-action',function(){
//         var id_arr = [];

//         $(".checkbox").each(function() {
//             id_arr.push($(this).val);
//         });

//         console.log(id_arr);
//     });
// }

$(document).on('click','.multiple-action',function(){

        var id_arr = [];
        $(".checkbox").each(function() {
            var that = $(this);
            var ischecked= that.is(':checked');
            if(ischecked)
                id_arr.push(that.val());
        });
        var page_name = $.trim($("#page_name").val());
        // console.log(page_name);
        if(id_arr.length > 0)
        {
            id_arr = id_arr.join(',');
            swal({
                title: "Are you sure?",
                text: "All details related to it will be affected!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes",
                closeOnConfirm: false
            } , function () {
                // console.log("herer");
                var dataString = {_token:CSRF_TOKEN};
                // dataString.groups = '';
                var temp_url = '/group/delete_group';    
                var action = '';
                if(page_name == 'superadmin_user_listing')
                {
                    var temp_url = '/user/alterStatus';
                    var action = $.trim($('.action option:selected').val());
                    dataString.action = action;
                    dataString.users = id_arr;
                } else {
                    dataString.groups = id_arr;
                }

                    $.ajax({
                        url: SITE_URL + temp_url,
                        method: 'POST',
                        data: dataString,
                        success: function (response) 
                        {
                            var status = response.status;
                            
                            
                            if( status == 0) {
                                swal("Error!", response.msg, "error");
                            } else if (status == 1) 
                            {
                                swal("Success", response.msg, "success");
                                setTimeout(function(){
                                    location.reload();
                                },1000);
                                //groupEditTable.draw();
                            } 
                            // companyUsers();
                        }
                    });
                });
        } else {
            swal("Error","Please select atleast one option. ",'error')
        }

});

var pointsListing = $("#points-listing").DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: SITE_URL + '/points/listing',
        data: function (d) {
            // d.role_id      = $("#role_id").val();
            // d.search_query = $("#search_query").val();
        }
    },
    searching: false,
    columns: [
        {data: 'name'},
        {data: 'post'},
        {data: 'vote'},
        {data: 'approved'},
        {data: 'answers'},
        {data: 'solutions'},
        {data: 'comments'},
        {data: 'likes'},
        {data: 'total'},
        // {data: 'admin', sorting: false, orderable: false}
    ] 
});

var editProfile = $("#editProfile").DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: SITE_URL + '/points/listing',
        data: function (d) {
            d.user_id      = $.trim($("#user_id").val());
            // d.search_query = $("#search_query").val();
        }
    },
    searching: false,
    columns: [
        {data: 'post'},
        {data: 'vote'},
        {data: 'approved'},
        {data: 'answers'},
        {data: 'solutions'},
        {data: 'comments'},
        {data: 'likes'},
        {data: 'total'},
        // {data: 'admin', sorting: false, orderable: false}
    ] 
});
function removeMember(userid) {
    $('#user_'+userid).remove();
    $('#employees_listing option[value="'+userid+'"]').attr("disabled", false);
}


var my_group_points = $("#my-group-points").DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: SITE_URL + '/points/my_group_users',
        data: function (d) {
            // d.user_id      = $.trim($("#user_id").val());
        }
    },
    searching: false,
    columns: [
        {data: 'name'},
        {data: 'post'},
        {data: 'vote'},
        {data: 'approved'},
        {data: 'answers'},
        {data: 'solutions'},
        {data: 'comments'},
        {data: 'likes'},
        {data: 'total'},
    ] 
});
 

$("#create_group_modal").validate({
    submitHandler: function(form){
        var name = $.trim($("#grp_name").val());
        var desc = $.trim($("#grp_desc").val());
        var company = $("#company_id option:selected").val();
        console.log(company);
        var dataString = {_token: CSRF_TOKEN , name:name, desc:desc,company: company};
        if(company > 0)
        {

        } else
        {
            $("#company-warn").css('display','block');
            return false;
        }


        $.ajax({
            url: SITE_URL+"/group/addGroup",
            method: "POST",
            data: dataString,
            success: function(response){
                if(response.status == '1')
                {
                    $("#company_id").trigger('change');
                }
            }
        });
    }
});

