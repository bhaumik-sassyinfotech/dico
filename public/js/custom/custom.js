var companyTable = $('#company_table').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: 'http://localhost/dico/public/get_company',
        data: function (d) {
            d.company_name = $('input[name=company_name]').val();
        }
    },
    columns : [
            {data : 'id'},
            {data : 'company_name'},
            {data : 'description'},
            {data : 'anonymous'},
            {data : 'add_admin'},
            {data : 'actions'},
    ],
    searching : false              
});
$('#search-form').on('submit', function(e) {
        companyTable.draw();
        e.preventDefault();
});
$("#company_form").validate({
        rules: {
            company_name: {
                required: true,
            }
        },
        messages: {
            company_name: {
                required: 'This field is required',
            }
        }
    }); 
 //==================== security question =====================//
 $('#security_question_table').DataTable({
    processing: true,
    serverSide: true,
    ajax: 'http://localhost/dico/public/get_security_question',
    columns : [
            {data : 'id'},
            {data : 'question'},
            {data : 'actions'},
    ],
    searching : false              
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
        url: 'http://localhost/dico/public/get_employee',
        data: function (d) {
            d.employee_name = $('input[name=employee_name]').val();
            d.employee_email = $('input[name=employee_email]').val();
        }
    },
    columns : [
            {data : 'id'},
            {data : 'name'},
            {data : 'email'},
            {data : 'active'},
            {data : 'suspended'},
            {data : 'actions'},
    ],
    searching : false              
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
$('#employee-search-form').on('submit', function(e) {
        employeeTable.draw();
        e.preventDefault();
});    
//============================================================//
//====================== User Module ========================//
var userTable = $('#users-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "http://localhost/dico/public/user/list",
            data: function (d) {
            d.user_name = $('input[name=user_name]').val();
            d.user_email = $('input[name=user_email]').val();
            d.role_id = $('#role_id :selected').val();
        }
        },
        columns : [
                {data : 'id'},
                {data : 'name'},
                {data : 'email'},
                {data : 'role'},
                {data : 'active'},
                {data : 'suspended'},
                {data : 'actions'}
        ],
        searching : false              
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
                email: 'Enter valid email',
            },
            company_id: {
                required: 'This field is required',
            },
            role_id: {
                required: 'This field is required'
            }
        }
    }); 
    $('#user-search-form').on('submit', function(e) {
        userTable.draw();
        e.preventDefault();
});    
//============================================================//