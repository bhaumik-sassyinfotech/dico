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