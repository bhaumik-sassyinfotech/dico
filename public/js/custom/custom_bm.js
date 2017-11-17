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
    $('#users_listing').select2();
    $("#company_listing,#company_users,#group_owner").select2();
});
$("#createUserGroup").validate();
/*Ajax Call to fetch user of the company selected*/
$("#company_listing").change(function () {
    var that = $(this);
    var dataString = {_token: CSRF_TOKEN, company_id: that.val()};
    if(that.val() != "")
    {
        $.ajax({
            url: 'companyUsers',
            data: dataString,
            method: "POST",
            success: function (response) {
                $("#users_listing,#group_owner").empty().append($("<option></option>").val("").html("Select user"));
                var userData = response.data;
                $.each(userData, function (key, val) {
                    $("#users_listing,#group_owner").append("<option value='" + val.id + "'>" + val.name + "</option>");
                });
            }
        });
    }
});
/*group listing >> View -> admin\group\index */
var groupTable = $('#group_table').DataTable({
    processing: true,
    serverSide: true,
    ajax: SITE_URL + '/group/list',
    searching: false,
    columns: [
        {data: 'row'},
        {data: 'group_name'},
        {data: 'description'},
        {data: 'group_users_count'},
        {data: 'actions'}
    ]
});

var groupEditTable = $("#group_users_edit_table").DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: SITE_URL + '/group/editUsers',
        data: function (d) {
            d.group_id = $("#group_id").val();
            d.company_id = $("#company_id").val();
        }
    },
    searching: false,
    columns: [
        {data: 'row'},
        {data: 'user_detail.name' , name:'userDetail.name' },
        {data: 'admin'},
        {data: 'action'}
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
$("#add_user").click(function(event){
    //
    $("#group_users_edit_form").validate();
    event.preventDefault();
    var users = $("#company_users").val();
    var companyId = $("#company_id").val();
    var groupId = $("#group_id").val();
    var dataString = { _token: CSRF_TOKEN, addGroupUsers:1, users_list: users , company_id: companyId, group_id: groupId };
    $.ajax({
        method: "POST",
        url: SITE_URL + '/group/editUsers',
        data: dataString,
        success: function (response) {
            var status = response.status;
            if(status == 1) {
                swal("Success", response.msg, "success");
            }
            companyUsers();
            groupEditTable.draw();
        }

    })
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
            if(status === 1)
            {
                $.each(users,function (key,value) {
                    userSelect.append($("<option></option>").html(value.name).val(value.id));
                });
            }
        }
    });
}



