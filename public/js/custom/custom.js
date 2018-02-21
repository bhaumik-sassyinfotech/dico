var companyTable = $('#company_table').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: SITE_URL + '/get_company',
        data: function (d) {
            d.company_name = $('input[name=company_name]').val();

        }
    },
    columns: [
        {data: 'id'},
        {data: 'company_name'},
        {data: 'description'},
        {data: 'anonymous'},
        {data: 'add_admin'},
        {data: 'actions', sorting: false, orderable: false},
    ],
    searching: false
});
$('#search-form').on('submit', function (e) {
    companyTable.draw();
    e.preventDefault();
});
$("#company_form").validate({
    rules: {
        company_name: {
            required: true,
        }/*,
        file_upload: {
            required: true,
        }*/
    },
    messages: {
        company_name: {
            required: 'This field is required',
        }/*,
        file_upload: {
            required: 'This field is required',
        }*/
    }
});
//==================== security question =====================//
$('#security_question_table').DataTable({
    processing: true,
    serverSide: true,
    ajax: SITE_URL + '/get_security_question',
    columns: [
        {data: 'id'},
        {data: 'question'},
        {data: 'actions', sorting: false, orderable: false},
    ],
    searching: false
});
$("#security_question_form").validate({
    rules: {
        question: {
            required: true,
        }
    },
    messages: {
        question: {
            required: 'This field is required',
        }
    }
});
//============================================================//
//==================== employee =====================//
var employeeTable = $('#employee_table').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: SITE_URL + '/get_employee',
        data: function (d) {
            d.employee_name = $('input[name=employee_name]').val();
            d.employee_email = $('input[name=employee_email]').val();
        }
    },
    columns: [
        {data: 'id'},
        {data: 'name'},
        {data: 'email'},
        {data: 'active'},
        {data: 'suspended'},
        {data: 'actions', sorting: false, orderable: false},
    ],
    searching: false
});
$("#employee_form").validate({
    rules: {
        employee_name: {
            required: true,
        },
        employee_email: {
            required: true,
            email: true
        },
        role_id: {
            required: true
        }
    },
    messages: {
        employee_name: {
            required: 'This field is required',
        },
        employee_email: {
            required: 'This field is required',
            email: 'Enter valid email',
        },
        role_id: {
            required: 'This field is required'
        }
    }
});
$('#employee-search-form').on('submit', function (e) {
    employeeTable.draw();
    e.preventDefault();
});
//============================================================//
//====================== User Module ========================//
var userTable = $('#users-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: SITE_URL + "/user/list",
        data: function (d) {
            d.user_name = $('input[name=user_name]').val();
            d.user_email = $('input[name=user_email]').val();
            d.role_id = $('#role_id :selected').val();
            d.company_id = $('#company_id :selected').val();
        }
    },
    columns: [
        {data: 'id'},
        {data: 'name'},
        {data: 'email'},
        {data: 'company.0.company_name'},
        {data: 'role'},
        {data: 'active'},
        {data: 'suspended'},
        {data: 'actions', sorting: false, orderable: false}
    ],
    searching: false
});

var companyuserTable = $('#company-users-table').DataTable({
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
        {data: 'name'},
        {data: 'email'},
        {data: 'role'},
        {data: 'followers_count'},
        {data: 'following_count'},
        {data: 'points'},
        // {data: 'admin', sorting: false, orderable: false}
    ]
});

$("#user_form").validate({
    rules: {
        user_name: {
            required: true,
        },
        user_email: {
            required: true,
            email: true
        },
        company_id: {
            required: true
        },
        role_id: {
            required: true
        }
    },
    messages: {
        user_name: {
            required: 'This field is required',
        },
        user_email: {
            required: 'This field is required',
            email: 'Enter valid email'
        },
        company_id: {
            required: 'This field is required',
        },
        role_id: {
            required: 'This field is required'
        }
    },
    submitHandler: function(form) {
            form.submit();
         }
});
$('#user-search-form').on('submit', function (e) {
    userTable.draw();
    e.preventDefault();
});
$('#company-user-search-form').on('submit', function (e) {
    companyuserTable.draw();
    e.preventDefault();
});
//============================================================//
//=================== Update Profile ========================//
$('#general_profile_form').validate({
    rules: {
        name: {
            required: true,
        },
        email: {
            required: true,
            email: true
        }
    },
    messages: {
        name: {
            required: 'This field is required',
        },
        email: {
            required: 'This field is required',
            email: 'Enter valid email',
        }
    }
});
$('#security_profile_form').validate();
$('#changepassword_form').validate({
    rules: {
        old_password: {
            required: true,
            minlength: 6,
        },
        new_password: {
            required: true,
            minlength: 6,
        },
        confirm_password: {
            required: true,
            minlength: 6,
            equalTo: "#new_password",
        }
    },
    messages: {
        old_password: {
            required: 'This field is required',
            minlength: 'Please enter atleast 6 characters',
        },
        new_password: {
            required: 'This field is required',
            minlength: 'Please enter atleast 6 characters',
        },
        confirm_password: {
            required: 'This field is required',
            minlength: 'Please enter atleast 6 characters',
            equalTo: "Enter Confirm Password Same as Password",
        }
    }
});

function uploadimage(input, id) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $("#" + id).attr('height', '150');
            $("#" + id).attr('width', '150');
            document.getElementById(id).src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }

}

//============================================================//
//====================== Points Module ========================//
$('#points_form').validate({
    rules: {
        activity: {
            required: true,
        },
        points: {
            required: true,
            number: true
        }
    },
    messages: {
        activity: {
            required: 'This field is required',
        },
        points: {
            required: 'This field is required',
            number: 'Please enter numbers only',
        }
    }
});
var pointsTable = $('#points_table').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: SITE_URL + '/get_points',
        data: function (d) {
            d.activity = $('input[name=activity]').val();
        }
    },
    columns: [
        {data: 'id'},
        {data: 'activity'},
        {data: 'points'},
        {data: 'notes'},
        {data: 'actions', sorting: false, orderable: false},
    ],
    searching: false
});
$('#points-search-form').on('submit', function (e) {
    pointsTable.draw();
    e.preventDefault();
});
//============================================================//
//====================== Post Module ========================//
$('#post_form').validate({
    rules: {
        post_type: {
            required: true,
        },
        post_title: {
            required: true,
            maxlength: POST_TITLE_LIMIT,
        }
    },
    messages: {
        post_type: {
            required: 'This field is required',
        },
        post_title: {
            required: 'This field is required',
            maxlength: 'you can enter only '+POST_TITLE_LIMIT+' characters',
        }
    },
    errorPlacement: function (error, element) {
        if (element.attr("name") == "post_type") {
            error.appendTo("#err_post_type");
        } else {
            error.insertAfter(element);
        }
    }
});
$('#commentbox_form').validate({
    rules: {
        comment_text: {
            required: true,
        }
    },
    messages: {
        comment_text: {
            required: 'This field is required',
        }
    }
});
$('#comment_replybox_form').validate({
    rules: {
        comment_reply_textarea: {
            required: true,
        }
    },
    messages: {
        comment_reply_textarea: {
            required: 'This field is required',
        }
    }
});
/*var postTable = $('#post_table').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: SITE_URL+'/get_post',

    },
    columns : [
            {data : 'id'},
            {data : 'post_title'},
            {data : 'post_description'},
            {data : 'post_user.name'},
            {data : 'created_at'},
            {data : 'post_status'},
            {data: 'actions',sorting: false, orderable: false},
    ],
    searching : false              
});*/
$('#post_flagged_form').validate({
    rules: {
        post_message_autor: {
            required: true,
        }
    },
    messages: {
        post_message_autor: {
            required: 'This field is required',
        }
    }
});
$('#comment_flagged_form').validate({
    rules: {
        comment_message_autor: {
            required: true,
        }
    },
    messages: {
        comment_message_autor: {
            required: 'This field is required',
        }
    }
});
$('#reply_form').validate({
    rules: {
        comment_reply_text: {
            required: true,
        }
    },
    messages: {
        comment_reply_text: {
            required: 'This field is required',
        }
    }
});
function editComment(id) {
    $('#comment_text_' + id).removeProp('readonly').slideDown('fast');
    $('#update_comment_' + id).css('display', 'inline-block');
    $('#comment_text_'+id).css('background-color','white');
    $("#comment_disp_"+id).slideUp('fast');
    $('#cancel_comment_'+id).css('display','inline-block');
}
function editCommentReply(id) {
    $('#comment_reply_text_' + id).removeProp('readonly').slideDown('fast');
    $('#update_comment_reply_' + id).css('display', 'inline-block');
    $('#comment_reply_text_'+id).css('background-color','white');
    $("#comment_reply_text_disp_"+id).slideUp('fast');
    $('#cancel_comment_reply_'+id).css('display','inline-block');
}
function closeComment(id) {
    $('#comment_text_'+id).removeProp('readonly').slideUp('fast');
    $('#update_comment_' + id).css('display', 'none');
    $('#comment_text_'+id).css('background-color','transparent');
    $("#comment_disp_"+id).slideDown('fast');
    $('#cancel_comment_'+id).css('display','none');
}
function closeCommentReply(id) {
        /*$('#comment_reply_text_'+id).attr('readonly',true);
        $('#comment_reply_text_'+id).css('background-color','transparent');
        $('#update_comment_reply_'+id).css('display','none');
        $('#cancel_comment_reply_'+id).css('display','none');*/
        $('#comment_reply_text_' + id).removeProp('readonly').slideUp('fast');
        $('#update_comment_reply_' + id).css('display', 'none');
        $('#comment_reply_text_'+id).css('background-color','transparent');
        $("#comment_reply_text_disp_"+id).slideDown('fast');
        $('#cancel_comment_reply_'+id).css('display','none');
    }
     function checkEmail(email,user_id) {
        if (email)
        {
            $("#spinner").show();
            $.ajax({
                url: SITE_URL + '/checkEmailExists',
                type: "POST",
                data: {email: email,_token: CSRF_TOKEN,user_id:user_id},
                success: function (data)
                {
                    var response = JSON.parse(data);
                    if (response.status == 1)
                    {
                        $('#emailerror').removeClass('hidden');
                        $('#emailerror').css({'display': 'block', 'color': '#FF0000'});
                        $('#emailerror').text(response.msg);
                        $('#for_me_emailerror').val('1');//exist
                        return  false;
                    } else
                    {
                        $('#emailerror').text('');
                        $('#emailerror').hide();
                        $('#for_me_emailerror').val('2');//not exist
                    }
                },
                complete: function (data) {
                    $("#spinner").hide();
                }
            });
        } else
        {
            $('#emailerror').hide();
        }
        }
//============================================================//
//============= Rajesh =================//

//==================== Block section =====================//
$('#blockList').DataTable({
    processing: true,
    serverSide: true,
    ajax: SITE_URL + '/blockList',
    columns: [
        {data: 'id'},
        {data: 'title'},
        {data: 'description'},
        {data: 'actions', sorting: false, orderable: false},
    ],
});
$("#blockEdit").validate({
    rules: {
        title: {
            required: true,
        },
        description: {
            required: true,
        }
    },
    messages: {
        title: {
            required: 'This field is required',
        },
        description: {
            required: 'This field is required',
        }
    }
});
//============================================================//

//==================== Packages section =====================//
$('#packageList').DataTable({
    processing: true,
    serverSide: true,
    ajax: SITE_URL + '/packagesList',
    columns: [
        {data: 'id'},
        {data: 'name'},
        {data: 'amount'},
        {data: 'total_user'},
        {data: 'actions', sorting: false, orderable: false},
    ],
});
$("#packageEdit").validate({
    rules: {
        name: {
            required: true,
        },
        amount: {
            required: true,
        },
        total_user: {
            required: true,
        }
    },
    messages: {
        name: {
            required: 'This field is required',
        },
        amount: {
            required: 'This field is required',
        },
        total_user: {
            required: 'This field is required',
        }
    }
});
//============================================================//

//==================== FAQS section =====================//
$('#adminfaqList').DataTable({
    processing: true,
     ordering: true,
    serverSide: true,
    ajax: SITE_URL + '/faqList',
    columns: [
        {data: 'id'},
        {data: 'question'},
        {data: 'answer'},
        {data: 'actions', sorting: false, orderable: false},
    ],
});
$("#faqsAdd").validate({
    rules: {
        question: {
            required: true,
        },
        answer: {
            required: true,
        }
    },
    messages: {
        question: {
            required: 'This field is required',
        },
        answer: {
            required: 'This field is required',
        }
    }
});
$("#faqsEdit").validate({
    rules: {
        question: {
            required: true,
        },
        answer: {
            required: true,
        }
    },
    messages: {
        question: {
            required: 'This field is required',
        },
        answer: {
            required: 'This field is required',
        }
    }
});
//============================================================//

//==================== FAQS Listing =====================//
$('#faqsList').DataTable({
    processing: true,
    serverSide: true,
    ajax: SITE_URL + '/faqList',
    columns: [
        {data: 'id'},
        {data: 'question'},
        {data: 'answer'},
        {data: 'actions', sorting: false, orderable: false},
    ],
});
$("#faqsAdd").validate({
    rules: {
        question: {
            required: true,
        },
        answer: {
            required: true,
        }
    },
    messages: {
        question: {
            required: 'This field is required',
        },
        answer: {
            required: 'This field is required',
        }
    }
});
//============================================================//
//==================== Contact Us section =====================//
//$('#contactUsList').DataTable({
//    processing: true,
//    serverSide: true,
//    ajax: SITE_URL + '/contactUsList',
//    columns: [
//        {data: 'id'},
//        {data: 'name'},
//        {data: 'email'},
//        {data: 'mobile'},
//        {data: 'message'},
//        {data: 'actions', sorting: false, orderable: false},
//    ],
//});

//=============================================================//
//========================Settings=======================//
$("#SettingsEdit").validate({
    rules: {
        email1: {
            required: true,
        },
        support_email: {
            required: true,
        },
        copyright: {
            required: true,
        },
        mobile: {
            required: true,
        }
        ,
        twitter: {
            url: true,
        },
        facebook: {
            url: true,
        },
        instagram: {
            url: true,
        }
    },
    messages: {
        email1: {
            required: 'This field is required',
        },
        support_email: {
            required: 'This field is required',
        },
        copyright: {
            required: 'This field is required',
        },
        mobile: {
            required: 'This field is required',
        },
        twitter: {
            url: 'Please enter proper url',
        },
        facebook: {
            url: 'Please enter proper url',
        },
        instagram: {
            url: 'Please enter proper url',
        }
    }
});
//=======================================================//
//=====================Reset admin password ==================================//
 $("#updateForgotPassword").validate({
        rules: {

            password: {
                required: true,
                minlength: 6
            },
            confirmPassword: {
                required: true,
                equalTo: "#password"
            }
        },
        messages: {
            password: {
                required: 'This field is required',
                minlength: 'Password must be a minimum 6 characters',
            },
            confirmPassword: {
                required: 'This field is required',
                equalTo: 'Password not match'
            }
        }
    });
//=======================================================//
//=====================Forgot email  admin side ==================================//
   $("#forgot_form").validate({
        rules: {
            email: {
                required: true,
                email: true
            }
        },
        messages: {
            email: {
                required: 'This field is required',
                email: 'Please enter a valid email address.'
            }
        }
    });
//=======================================================//
//=======================================================//
 $("#feedbackAdd").validate({
    rules: {
        subject: {
            required: true,
        },
        description: {
            required: true,
        }
    },
    messages: {
        subject: {
            required: 'This field is required',
        },
        description: {
            required: 'This field is required',
        }
    }
});
//=======================================================//
  $("#supportAdd").validate({
    rules: {
        issue: {
            required: true,
        },
        description: {
            required: true,
        }
    },
    messages: {
        issue: {
            required: 'This field is required',
        },
        description: {
            required: 'This field is required',
        }
    }
});
//=======================================================//
//==================== Feedback section =====================//
$('#feedbackList').DataTable({
    processing: true,
    serverSide: true,
    ajax: SITE_URL + '/feedbackList',
    columns: [
        {data: 'id'},
        {data: 'subject'},
        {data: 'description'},       
        {data: 'actions', sorting: false, orderable: false},
    ],
});

//=============================================================//
//==================== Support section =====================//
$('#supportList').DataTable({
    processing: true,
    serverSide: true,
    ajax: SITE_URL + '/supportList',
    columns: [
        {data: 'id'},
        {data: 'issue'},
        {data: 'description'},       
        {data: 'actions', sorting: false, orderable: false},
    ],
});

//=============================================================//
//==================== Contact Us section =====================//
$('#contactUsList').DataTable({
    processing: true,
    serverSide: true,
    order: [ [0, 'desc'] ],
    ajax: SITE_URL + '/contactUsList',
    columns: [
        {data: 'id'},
        {data: 'name'},
        {data: 'email'},
        {data: 'mobile'},
        {data: 'message'},
        {data: 'actions', sorting: false, orderable: false},
    ],
});
//================================================================//
//==================== add member to group =======================//
$('#addMember').validate({
    rules: {
        user_email: {
            required: true,
        }
    },
    messages: {
        user_email: {
            required: 'This field is required',
        }
    }
});
$(document).on('click','.addMemberToGroup',function() {
      $('.addMemberPopup').find('input[name=member_group_id]').val($(this).data('group-id'));
      $('.addMemberPopup').find('input[name=member_company_id]').val($(this).data('company-id'));
});
$('#addGroupMember').click(function() {
    if($('#addMember').valid() == 1) {
        $("#spinner").show();
        var user_mail = $("#user_email").val();
        if (user_mail === false) return false;
        if (user_mail === "" || $.trim(user_mail) === "")
        {
            swal.showInputError("Please enter email address");
            return false
        }
        var pattern = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
        var reg = pattern.test(user_mail);
        if(!reg)
        {
            swal.showInputError("Please enter valid email address");
            return false;
        }
        var groupId   = $('#member_group_id').val();
        var companyId = $('#member_company_id').val();
        var dataString = {
            _token: CSRF_TOKEN,
            user: user_mail,
            company_id: companyId,
            group_id: groupId
        };
        $.ajax({
            url: SITE_URL + '/group/addUserByEmailAddress',
            method: "POST",
            data: dataString,
            success: function (response) {
                $("#spinner").hide();
                if(response.status == 0) {
                    swal("Error", response.msg, "error");
                    return false;
                }
                else if(response.status == 1) {
                    swal("Success", response.msg, "success");
                    location.reload();
                }
            }
        });
    } else {
        return false;
    }
});
$(document).on('click','.deleteGroup',function() {
    var groupId = $(this).data('group-id');
    swal({
        title: "Are you sure?",
        text: "All details related to it will be affected!",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Yes",
        closeOnConfirm: false
    } , function () {
        var dataString = {_token:CSRF_TOKEN};
        var temp_url = '/group/delete_group';    
        var action = '';
        dataString.groups = groupId;
        
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
                } 
            }
        });
    });
});
//================================================================//