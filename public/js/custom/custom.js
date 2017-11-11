$('#company_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: 'http://localhost/dico/public/get_company',
                        columns : [
                                {data : 'id'},
                                {data : 'company_name'},
                                {data : 'description'},
                                {data : 'anonymous'},
                                {data : 'add_admin'},
                                {data : 'actions'},
                          ],
        });