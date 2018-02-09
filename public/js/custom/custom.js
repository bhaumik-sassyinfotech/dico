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