@extends('layouts.layout')

@push('title')
<title>users-news-portal</title>
@endpush

@push('heading')
<h1 class="m-0 mb-1 text-success font-weight-bold ">Users</h1>
@endpush

@push('sub-heading')
<li class="breadcrumb-item active text-success font-weight-bold">Users</li>
@endpush

@section('content')
<div class="container-fluid">
    @if (session('success'))
    <div class="alert alert-success" role="alert">
        {{ session('success') }}
    </div>
    @endif
    <!-- Modal for editing -->
    <div class="modal fade" id="userModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h4 text-success" id="ModalLabel">Add user</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Form for editing user -->
                    <form id="userForm" method="POST">
                        @csrf
                        <!-- Hidden field to store user ID for editing -->
                        <input type="hidden" id="userId" name="userId">

                        <div class="form-group m-0 mb-1">
                            <label for="userName">Name</label>
                            <input type="text" class="form-control" placeholder="Enter Your Name" id="userName" name="userName">
                            <span class="text-danger font-weight-bold" id="error-userName"></span>
                        </div>

                        <div class="form-group m-0 mb-1">
                            <label for="userEmail">Email</label>
                            <input type="text" class="form-control" placeholder="Enter Your Email" id="userEmail" name="userEmail">
                            <span class="text-danger font-weight-bold" id="error-userEmail"></span>
                        </div>

                        <div class="form-group m-0 mb-1">
                            <label for="userPhone">Phone</label>
                            <input type="text" class="form-control" placeholder="Enter Your Phone" id="userPhone" name="userPhone">
                            <span class="text-danger font-weight-bold" id="error-userPhone"></span>
                        </div>

                        <div class="form-group m-0 mb-1" id="password_div">
                            <label for="userPassword">Password</label>
                            <input type="text" class="form-control" placeholder="Enter Your Password" id="userPassword" name="userPassword">
                            <span class="text-danger font-weight-bold" id="error-userPassword"></span>
                        </div>
                        <div class=" form-group m-0 mb-1">
                            <label for="gender">Gender</label>
                            <select name="userGender" class="form-select" id="gender">
                                <option selected>Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="form-group m-0 mb-1">
                            <label for="role">Role</label>
                            <select name="userRole" class="form-select" id="role">
                            <option selected>Select Role</option>
                                <option value="admin">Admin</option>
                                <option value="user">User</option>
                            </select>
                        </div>
                        <div class="form-group m-0 mb-1">
                            <label for="country">Country</label>
                            <select name="userCountry" class="form-select" id="country">
                                <option selected value="India">India</option>
                            </select>
                        </div>
                        <!-- Add more form fields as needed -->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="saveChangesBtn">Submit</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row p-2">
                        <div class="col-md-12">
                            <span class="h4 text-primary font-weight-bold">List Of Users</span>
                            <button type="button" id="addUser" class="btn btn-primary add-btn font-weight-bold float-right" data-bs-toggle="modal" data-bs-target="#userModal">Add</button>
                        </div>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div id="example2_wrapper">
                        <div class="row">
                            <div class="col-sm-12 col-md-6"></div>
                            <div class="col-sm-12 col-md-6"></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="display text-center" id="user_tbl">
                                    <thead class="bg-primary">
                                        <tr>
                                            <th>id</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Gender</th>
                                            <th>Role</th>
                                            <th>Country</th>
                                            <th>Login Time</th>
                                            <th>Created At</th>
                                            <th>Updated At</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot class="bg-primary">
                                        <tr>
                                            <th>id</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Gender</th>
                                            <th>Role</th>
                                            <th>Country</th>
                                            <th>Login Time</th>
                                            <th>Created At</th>
                                            <th>Updated At</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        var table = $('#user_tbl').DataTable({
            "processing": true,
            "serverSide": true,
            "autoWidth": false,
            "order": [
                [0, "desc"]
            ],
            "ajax": "{{ route('users') }}",
            "columns": [{
                    data: 'id',
                    defaultContent: 'NULL'
                },

                {
                    data: 'name',
                    defaultContent: 'NULL'
                },

                {
                    data: 'email',
                    defaultContent: 'NULL'
                },
                {
                    data: 'phone',
                    defaultContent: 'NULL'
                },
                {
                    data: 'gender',
                    defaultContent: 'NULL'
                },
                {
                    data: 'role',
                    defaultContent: 'NULL'
                },
                {
                    data: 'country',
                    defaultContent: 'NULL'
                },
                {
                    data: 'login_time',
                    render: function(data, type, row) {
                        return moment(row.login_time).format('DD/MM/YYYY HH:mm');
                    }
                },
                {
                    data: 'created_at',
                    render: function(data, type, row) {
                        return moment(row.created_at).format('DD/MM/YYYY HH:mm');
                    }
                },
                {
                    data: 'updated_at',
                    render: function(data, type, row) {
                        return moment(row.updated_at).format('DD/MM/YYYY HH:mm');
                    }
                },
                {
                    data: 'status',
                    render: function(data, type, row) {
                        var status = row.status;
                        return (status == 1) ?
                            '<span class="text-success font-weight-bold">Active</span>' :
                            '<span class="text-danger font-weight-bold">InActive</span>';
                    }
                },
                {
                    data: 'id',
                    render: function(data, type, row) {
                        var id = row.id;
                        var status = row.status;

                        var btnClass = status == 1 ? 'btn-success btn-sm font-weight-bold' :
                            'btn-danger btn-sm font-weight-bold';

                        var btnText = status == 1 ? 'Active' : 'Inactive';
                        var toggleStatus = status == 1 ? 0 : 1;

                        var statusButton =
                            '<button type="button" class="btn ' + btnClass +
                            '" onclick="statusUpdate(' + id + ')">' + btnText +
                            '</button>';

                        var editButton =
                            '<button class="btn btn-primary btn-sm edit-btn" value="' + row.id +
                            '">Edit</button>';

                        var deleteButton =
                            '<button class="btn btn-danger btn-sm delete-btn" value="' + row.id +
                            '">Delete</button>';

                        return statusButton + ' ' + editButton + ' ' + deleteButton;
                    }
                }
            ]
        });
    });

    $('#addUser').click(function() {
        $('#userId').val('')
        $('#userName').val('')
        $('#userPassword').val('')
        $('#userEmail').val('')
        $('#userPhone').val('')
        $('#categoryForm').attr('action', "{{ route('categories.add') }}");
        $('#categoryForm').attr('method', "POST");
        $('#password_div').removeClass('d-none');
        $('#ModalLabel').html('Add User');
    });

    $(document).ready(function() {
        $('#userForm').on('submit', function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '{{ route("users.add") }}',
                data: formData,

                success: function(response) {
                    // console.log(response);
                    if (response.status == 200) {
                        $('#userModal').modal('hide');
                        Swal.fire(response.message);
                        $('#user_tbl').DataTable().ajax.reload(null, false);
                    }
                },
                error: function(xhr) {
                    console.log(xhr);
                    // If validation fails, handle validation errors
                    var errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        // Display validation error messages to the user
                        $('#error-' + key).text(value);
                    });
                }
            });
        });
    });

    $('#user_tbl').on('click', '.edit-btn', function() {
        $('#password_div').addClass('d-none');
        var userId = $(this).val();
        $('#userId').val(userId);
        $('#userForm').attr('action', "");
        $('#userForm').attr('method', "");
        $('#userModal').modal('show');
        $('#ModalLabel').html('Edit user');
        $.ajax({
            type: "GET",
            url: "edit-user/" + userId,
            success: function(response) {
                if (response.status == 200) {
                    $('#userId').val(response.user.id);
                    $('#userName').val(response.user.name);
                    $('#userEmail').val(response.user.email);
                    $('#userPhone').val(response.user.phone);
                    $('#gender').val(response.user.gender);
                    $('#role').val(response.user.role);
                    $('#country').val(response.user.country);
                } else {
                    Swal.fire(response.message);
                }
            }
        });
    });

    $('#user_tbl').on('click', '.delete-btn', function() {
        var deleteId = $(this).val();
        console.log(deleteId);
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "GET",
                    url: "delete-user/" + deleteId,
                    success: function(response) {
                        // console.log(response);
                        if (response.status == 200) {
                            Swal.fire({
                                title: "Deleted!",
                                text: "User has been deleted.",
                                icon: "success"
                            });
                            $('#user_tbl').DataTable().ajax.reload(null,
                                false);
                        } else if (response.message == 404) {
                            Swal.fire(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            } else {
                Swal.fire({
                    title: "Cancelled!",
                    text: "Not Deleted",
                    icon: "Failed"
                });
            }
        });
    });
</script>
@endsection