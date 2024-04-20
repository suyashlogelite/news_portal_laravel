@extends('layouts.layout')

@push('title')
<title>EditNewsPost-news-portal</title>
@endpush

@push('heading')
<h1 class="m-0 text-success font-weight-bold h3">Edit News Posts</h1>
@endpush

@push('sub-heading')
<li class="breadcrumb-item active text-success font-weight-bold">Edit News Posts</li>
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
                <div class="card-header bg-dark">
                    <div class="row p-2">
                        <div class="col-md-12">
                            <span class="h4 text-success font-weight-bold">Edit Posts Form</span>
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
                                <form id="editNewsPost" enctype="multipart/form-data" method="POST">
                                    @csrf
                                    <input type="hidden" value="{{ $newsPost->id }}" name="newsId" id="newsId">

                                    <div class="row">
                                        <div class="col-md-12 form-group">
                                            <label for="newsHeading" class="form-label">Heading</label>
                                            <input value="{{ $newsPost->heading }}" type="text" name="newsHeading" class="form-control" id="newsHeading">
                                            <span class="text-danger font-weight-bold" id="error-newsHeading"></span>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label for="newsCategory" class="form-label">Category</label>
                                            <select name="newsCategory" id="newsCategory" class="select2 form-select" aria-label="Select Category">
                                                <option selected disabled>Select Category</option>
                                                @foreach($newsCat as $category)
                                                <option value="{{ $category->id }}" {{ $category->id == $newsPost->category_id ? 'selected' : '' }}>
                                                    {{ $category->category_name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger font-weight-bold" id="error-newsCategory"></span>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label for="newsTags" class="form-label">Related Tags</label>
                                            <select id="multipleSelect" name="newsTags[]" class="select2 form-control" multiple="multiple" data-placeholder="Select Tags">
                                                @foreach($newsTag as $tag)
                                                <option value="{{ $tag->tag_name }}" {{ in_array($tag->tag_name, $selectedTags) ? 'selected' : '' }}>
                                                    {{ $tag->tag_name }}
                                                </option>
                                                @endforeach
                                            </select>

                                            <span class="text-danger font-weight-bold" id="error-newsTags"></span>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12 form-group">
                                            <label for="newsContent" class="form-label">Content</label>
                                            <textarea name="newsContent" class="form-control" id="newsContent" rows="10" cols="50">{{ $newsPost->content }}</textarea>
                                            <span class="text-danger font-weight-bold" id="error-newsContent"></span>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-8 form-group">
                                            <label for="newsImage" class="form-label">Image</label>
                                            <input class="form-control" type="file" id="newsImage" name="newsImage">
                                            <span class="text-danger font-weight-bold" id="error-newsImage"></span>
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <label for="previewImage" class="form-label">Preview</label><br>
                                            <img id="previewImage" src="{{ $newsPost->image ?: 'placeholder.jpg' }}" alt="Preview Image" height="100">
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-success btn-sm form-control font-weight-bold" name="submit">Submit</button>
                                </form>

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

<script src="https://cdn.ckeditor.com/ckeditor5/41.3.1/classic/ckeditor.js"></script>

<script type="text/javascript">
    $(document).ready(function() {

        ClassicEditor
            .create(document.querySelector('#newsContent'), {
                ckfinder: {
                    uploadUrl: "{{ route('ckeditor.upload') }}?_token={{ csrf_token() }}"
                }
            })
            .then(editor => {
                console.log(editor);
            })
            .catch(error => {
                console.error(error);
            });

        $('.select2').select2({
            tags: true, // Allows user to create new tags
            tokenSeparators: [',', ' '], // Define separators for multiple tags
            createTag: function(params) {
                var term = $.trim(params.term);

                if (term === '') {
                    return null;
                }

                var found = false;
                // Check if the term already exists in the options
                $('.select2 option').each(function() {
                    if ($.trim($(this).text()) === term) {
                        found = true;
                        return false; // Exit the loop early
                    }
                });

                if (found) {
                    return null; // Return null to prevent tag creation
                }

                return {
                    id: term,
                    text: term,
                    newTag: true // Add custom property to identify new tag
                };
            }
        });
    });

    $(document).ready(function() {
        $('#editNewsPost').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            console.log(formData);
            $.ajax({
                type: 'POST',
                url: '{{ route("post.add") }}',
                data: formData,
                contentType: false,
                processData: false,

                success: function(response) {
                    if (response.status == 200) {
                        if (response.status == 200) {
                            console.log(response.message);
                            window.location.href = "news";
                        }
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

    $('#newsImage').change(function() {
        var input = this;
        var Preview = $('#previewImage');

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                Preview.attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            Preview.attr('src', "");
        }
    });
</script>
@endsection