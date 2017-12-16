@extends('template.default')
<title>DICO - User</title>
@section('content')

<div id="page-content">
    <div id='wrap'>
        <div id="page-heading">
            <ol class="breadcrumb">
                <li><a href="{{ url('/home') }}">Dashboard</a></li>
                <li class="active">User</li>
            </ol>
            <h1>User</h1>
            <div class="options">
                <div class="btn-toolbar">
                    <div class="btn-group hidden-xs">
                        <a href="{{ route('user.create') }}" class="btn btn-primary">Add New</a>
                    </div>
                </div>
            </div>
        </div>    
        <div class="container">
            <div class="panel panel-default">
                <div class="panel-body">

                    @if(session()->has('success'))
                    <div class="alert alert-success">
                        {{ session()->get('success') }}
                    </div>
                    @endif
                    @if(session()->has('err_msg'))
                    <div class="alert alert-danger">
                        {{ session()->get('err_msg') }}
                    </div>
                    @endif
                    <form method="POST" id="company-user-search-form" class="form-inline" role="form">
                        <div class="form-group">
                            <label for="name">User Name</label>
                            <input type="text" class="form-control" name="user_name" id="user_name" placeholder="user name">
                        </div>
                        <div class="form-group">
                            <label for="email">User Email</label>
                            <input type="text" class="form-control" name="user_email" id="user_email" placeholder="user email">
                        </div>
                        <div class="form-group">
                            <label for="role">Role</label>
                            <select id="role_id" name="role_id" class="form-control">
                                    <option value="">------ Select ------</option>
                                <?php
                                    if(!empty($roles)) {
                                        foreach($roles as $role) {
                                            ?>
                                    <option value="{{$role->id}}">{{$role->role_name}}</option>
                                            <?php
                                        }
                                    }
                                ?>
                            </select>    
                        </div>
                        <button type="submit" class="btn btn-primary">Search</button>
                    </form>
                    <table class="table table-striped" id="company-users-table">
                        <thead>
                            <tr>
                                <th>ID#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Active</th>
                                <th>Suspended</th>
                                <th>Actions</th>
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

</script>
@endpush
