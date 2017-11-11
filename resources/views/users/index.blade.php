@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Dashboard</div>

                    <div class="panel-body">
                        <table class="table table-striped" id="users-table">
                            <thead>
                                <tr>
                                    <th>User ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('user.list') }}',
                columns: [
                    {name: 'id'},
                    {name: 'name'},
                    {name: 'email'},
                ]
            });
        });
    </script>
@endpush
