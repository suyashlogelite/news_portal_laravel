@extends('layouts.layout')

@push('title')
<title>Tags-news-portal</title>
@endpush

@push('heading')
<h1 class="m-0 text-success font-weight-bold h3">Tags</h1>
@endpush

@push('sub-heading')
<li class="breadcrumb-item text-success font-weight-bold active">Tags</li>
@endpush

@section('content')
<div class="container-fluid">
    @if (session('success'))
    <div class="alert alert-success" role="alert">
        {{ session('success') }}
    </div>
    @endif
    <!-- Modal for editing -->
    <div class="modal fade" id="tagsModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel">Add Tags</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Form for editing category -->
                    <form id="tagsForm" method="POST">
                        @csrf
                        <!-- Hidden field to store category ID for editing -->
                        <input type="text" id="tagsId" name="tagsId">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="tagsStatus" class="form-select" id="tagsStatus">
                                <option value="1">Active</option>
                                <option value="0">InActive</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="tagName">Tag Name</label>
                            <input type="text" class="form-control" id="tagName" name="tagName">
                            <span class="text-danger font-weight-bold" id="error-tagName"></span>
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
                            <span class="h4">List Of Tags</span>
                            <button type="button" id="addTag" class="btn btn-primary add-btn font-weight-bold float-right" data-bs-toggle="modal" data-bs-target="#tagsModal">Add</button>
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
                                <table class="display text-center" id="tag_tbl">
                                    <thead class="bg-primary">
                                        <tr>
                                            <th>id</th>
                                            <th>Tag Name</th>
                                            <th>Created At</th>
                                            <th>Updated At</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot class="bg-primary">
                                        <tr>
                                            <th>id</th>
                                            <th>Tag Name</th>
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
        var table = $('#tag_tbl').DataTable({
            "processing": true,
            "serverSide": true,
            "autoWidth": false,
            "order": [
                [0, "desc"]
            ],
            "ajax": "{{ route('tags') }}",
            "columns": [{
                    data: 'id'
                },

                {
                    data: 'tag_name'
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

    // Tags Master Script

    function statusUpdate(id) {
        console.log(id);
        $.ajax({
            url: "status-tag/" + id,
            type: "GET",
            success: function(response) {
                console.log(response);
                $('#tag_tbl').DataTable().ajax.reload(null, false);
            }
        });
    }

    $('#addTag').click(function() {
        $('#tagsId').val('');
        $('#tagName').val('');
        $('#ModalLabel').html('Add Tags');
    });

    $(document).ready(function() {
        $('#tagsForm').on('submit', function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '{{ route("tags.add") }}',
                data: formData,
                success: function(response) {
                    // console.log(response.message);
                    if (response.status == 200) {
                        $('#tagsModal').modal('hide');
                        Swal.fire(response.message);
                        $('#tag_tbl').DataTable().ajax.reload(null, false);
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

    $('#tag_tbl').on('click', '.edit-btn', function() {
        var tagId = $(this).val();
        $('#tagId').val(tagId);
        $('#tagsModal').modal('show');
        $('#ModalLabel').html('Edit Tags');
        console.log('Edit button clicked for tag ID:', tagId);
        $.ajax({
            type: "GET",
            url: "edit-tag/" + tagId,
            success: function(response) {
                console.log(response);
                if (response.status == 404) {
                    Swal.fire(response.message);
                } else {
                    $('#tagsId').val(response.tag.id);
                    $('#tagName').val(response.tag.tag_name);
                }
            }
        });
    });

    $('#tag_tbl').on('click', '.delete-btn', function() {
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
                    url: "delete-tag/" + deleteId,
                    success: function(response) {
                        // console.log(response);
                        if (response.status == 200) {
                            Swal.fire({
                                title: "Deleted!",
                                text: "Tag has been deleted.",
                                icon: "success"
                            });
                            $('#tag_tbl').DataTable().ajax.reload(null,
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