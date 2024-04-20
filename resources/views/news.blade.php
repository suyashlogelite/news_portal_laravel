@extends('layouts.layout')

@push('title')
<title>posts-news-portal</title>
@endpush

@push('heading')
<h1 class="m-0 text-success font-weight-bold h3">Posts</h1>
@endpush

@push('sub-heading')
<li class="breadcrumb-item active text-success font-weight-bold">Posts</li>
@endpush

@section('content')
<div class="container-fluid">
    @if (session('success'))
    <div class="alert alert-success" role="alert">
        {{ session('success') }}
    </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row p-2">
                        <div class="col-md-12">
                            <span class="h4 font-weight-bold text-primary">List Of Posts</span>
                            <a id="addPost" href="addNewsForm"
                                class="btn btn-primary add-btn font-weight-bold float-right">Add</a>
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
                                <table class="display text-center" id="news_tbl">
                                    <thead class="bg-primary">
                                        <tr class="fs-5">
                                            <th>id</th>
                                            <th>Heading</th>
                                            <th>Image</th>
                                            <th style="min-width: 110px;">Created By</th>
                                            <th>Views</th>
                                            <th>Status</th>
                                            <th style="min-width: 110px;">Created At</th>
                                            <th style="min-width: 110px;">Updated At</th>
                                            <th style="min-width: 130px;">Manage Posts</th>
                                        </tr>
                                    </thead>
                                    <tfoot class="bg-primary">
                                        <tr class="fs-5">
                                            <th>id</th>
                                            <th>Heading</th>
                                            <th>Image</th>
                                            <th style="min-width: 110px;">Created By</th>
                                            <th>Views</th>
                                            <th>Status</th>
                                            <th style="min-width: 110px;">Created At</th>
                                            <th style="min-width: 110px;">Updated At</th>
                                            <th style="min-width: 130px;">Manage Posts</th>
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
    var table = $('#news_tbl').DataTable({

        "processing": true,
        "serverSide": true,
        "autoWidth": false,

        "order": [
            [0, "desc"]
        ],

        "ajax": "{{ route('news') }}",

        "columns": [{
                data: 'id'
            },

            {
                data: 'heading'
            },

            {
                data: 'image',
                render: function(data, type, row) {
                    var image = row.image;
                    return '<img src="' + image + '" alt=Image height=80 width=100>';
                }
            },

            {
                data: 'created_by'
            },

            {
                data: 'views',
                defaultContent: '0'
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
                data: 'null',
                render: function(data, type, row) {
                    var id = row.id;
                    var status = row.status;

                    var btnClass = status == 1 ? 'btn-success btn-sm font-weight-bold' :
                        'btn-danger btn-sm font-weight-bold';

                    var btnText = status == 1 ? '<i class="fa-solid fa-toggle-on"></i>' :
                        '<i class="fa-solid fa-toggle-off"></i>';
                    var toggleStatus = status == 1 ? 0 : 1;

                    var statusButton =
                        '<button type="button" class="btn ' + btnClass +
                        '" onclick="statusUpdate(' + id + ')">' + btnText +
                        '</button>';

                    var viewPost =
                        '<button type="button" class="btn btn-info btn-sm view-btn" value="' +
                        row.id +
                        '"><i class="fa-solid fa-eye"></i></button>';

                    var editButton =
                        '<button class="btn btn-primary btn-sm edit-btn" value="' + row.id +
                        '"><i class="fa-solid fa-square-pen"></i></button>';

                    var deleteButton =
                        '<button class="btn btn-danger btn-sm delete-btn" value="' + row.id +
                        '"><i class="fa-solid fa-trash-can"></i></button>';

                    return statusButton + ' ' + viewPost + ' ' + editButton + ' ' +
                        deleteButton;
                }
            }
        ]
    });
});

$('#news_tbl').on('click', '.edit-btn', function() {
    var id = $(this).val();
    console.log(id);
    window.location.href = 'editNewsForm?id=' + id;
});

$('#news_tbl').on('click', '.view-btn', function() {
    var id = $(this).val();
    console.log(id);
    window.location.href = 'viewPost?id=' + id;
});

function statusUpdate(id) {
    console.log(id);
    $.ajax({
        url: "status-news/" + id,
        type: "GET",
        success: function(response) {
            console.log(response);
            $('#news_tbl').DataTable().ajax.reload(null, false);
        }
    });
}

$('#news_tbl').on('click', '.delete-btn', function() {
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
                url: "delete-post/" + deleteId,
                success: function(response) {
                    // console.log(response);
                    if (response.status == 200) {
                        Swal.fire({
                            title: "Deleted!",
                            text: "Post has been deleted.",
                            icon: "success"
                        });
                        $('#news_tbl').DataTable().ajax.reload(null,
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