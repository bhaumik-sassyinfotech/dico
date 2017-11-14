
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
});
$("#createUserGroup").validate();
/*Ajax Call to fetch user of the company selected*/
$("#company_listing").change(function() {
    var that = $(this);
    var dataString = { _token: CSRF_TOKEN , company_id: that.val() };
    $.ajax({
        url: 'companyUsers',
        data: dataString,
        method: "POST",
        success: function (response) {
            $("#users_listing").empty();
            var userData = response.data;
            $.each(userData,function (key , val) {
                $("#users_listing").append("<option value='"+val.id+"'>"+val.name+"</option>");
            });
        }
    });
});
/*group listing >> View -> admin\group\index */
var groupTable = $('#group_table').DataTable({
    processing: true,
    serverSide: true,
    ajax: SITE_URL+'/group/list',
    columns : [
        {data : 'id'},
        {data : 'group_name'},
        {data : 'description'},
        {data : 'group_users_count.cnt'},
    ],
    searching : false
});