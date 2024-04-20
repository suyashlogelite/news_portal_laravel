@extends('layouts.layout')

@push('title')
<title>categories-news-portal</title>
@endpush

@push('heading')
<h1 class="m-0 text-success font-weight-bold h3">Categories</h1>
@endpush

@push('sub-heading')
<li class="breadcrumb-item active text-success font-weight-bold">Categories</li>
@endpush

@section('content')
<div class="container-fluid">

    @if (session('success'))
    <div class="alert alert-success" role="alert">
        {{ session('success') }}
    </div>
    @endif

    <!-- Modal for editing -->
    <div class="modal fade" id="categoryModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel">Add Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Form for editing category -->
                    <form id="categoryForm" method="POST">
                        @csrf
                        <!-- Hidden field to store category ID for editing -->
                        <input type="hidden" id="categoryId" name="categoryId">
                        <div class="form-group">
                            <label for="parentCategory">Parent Category</label>
                            <select name="parentCategory" class="form-select" id="parentCategory">
                                <option value="0">No Parent</option>
                                @foreach($data as $category)
                                <option value="{{ $category->id }}">
                                    {{ $category->category_name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="categoryName">Category Name</label>
                            <input type="text" class="form-control" id="categoryName" name="categoryName" value="{{ isset($category) ? $category->category_name : '' }}">
                            <span class="text-danger font-weight-bold" id="error-categoryName"></span>
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
                            <span class="h4">List Of Categories</span>
                            <button type="button" id="addCategory" class="btn btn-primary add-btn font-weight-bold float-right" data-bs-toggle="modal" data-bs-target="#categoryModal">Add</button>
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
                                <table class="display text-center" id="category_tbl">
                                    <thead class="bg-primary">
                                        <tr>
                                            <th>id</th>
                                            <th>Parent_Category</th>
                                            <th>Category</th>
                                            <th>Created At</th>
                                            <th>Updated At</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot class="bg-primary">
                                        <tr>
                                            <th>id</th>
                                            <th>Parent_Category</th>
                                            <th>Category</th>
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
        var table = $('#category_tbl').DataTable({
            "processing": true,
            "serverSide": true,
            "autoWidth": false,
            "order": [
                [0, "desc"]
            ],
            "ajax": "{{ route('categories') }}",
            "columns": [{
                    data: 'id'
                },

                {
                    data: 'parent_category_name',
                    defaultContent: 'Root'
                },

                {
                    data: 'category_name'
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


    function statusUpdate(id) {
        console.log(id);
        $.ajax({
            url: "status-category/" + id,
            type: "GET",
            success: function(response) {
                console.log(response);
                $('#category_tbl').DataTable().ajax.reload(null, false);
            }
        });
    }

    $('#addCategory').click(function() {
        $('#categoryId').val('')
        $('#categoryName').val('')
        $('#categoryForm').attr('action', "{{ route('categories.add') }}");
        $('#categoryForm').attr('method', "POST");
        $('#ModalLabel').html('Add Category');
    });


    $('#category_tbl').on('click', '.edit-btn', function() {
        var categoryId = $(this).val();
        $('#categoryId').val(categoryId);
        $('#categoryModal').modal('show');
        $('#ModalLabel').html('Edit Category');
        $('#CategoryForm').attr('action', "");
        $('#CategoryForm').attr('method', "");
        console.log('Edit button clicked for category ID:', categoryId);
        $.ajax({
            type: "GET",
            url: "edit-category/" + categoryId,
            success: function(response) {
                if (response.status == 404) {
                    Swal.fire(response.message);
                } else {
                    $('#editCategoryId').val(response.category.id);
                    $('#categoryName').val(response.category.category_name);
                }
            }
        });
    });

    $(document).ready(function() {
        $('#categoryForm').on('submit', function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '{{ route("categories.add") }}',
                data: formData,
                success: function(response) {
                    // console.log(response);
                    if (response.status == 200) {
                        $('#categoryModal').modal('hide');
                        Swal.fire(response.message);
                        $('#category_tbl').DataTable().ajax.reload(null, false);
                    }
                },
                error: function(xhr) {
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

    $('#category_tbl').on('click', '.delete-btn', function() {
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
                    url: "delete-category/" + deleteId,
                    success: function(response) {
                        // console.log(response);
                        if (response.status == 200) {
                            Swal.fire({
                                title: "Deleted!",
                                text: "Category has been deleted.",
                                icon: "success"
                            });
                            $('#category_tbl').DataTable().ajax.reload(null,
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