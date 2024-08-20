@extends('layouts.layout')

@push('title')
<title>viewPost-news-portal</title>
@endpush

@push('heading')
<h1 class="m-0 text-success font-weight-bold h3">View Post</h1>
@endpush

@push('sub-heading')
<li class="breadcrumb-item active text-success font-weight-bold">View Post</li>
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
            <div class="row p-2">
                <div class="col-md-12">
                    <a href="news" type="button" class="btn btn-primary add-btn font-weight-bold float-right">All Post</a>
                </div>
            </div>
            <!-- /.card-header -->
            <p class="text-success p-3 mb-2 border border-3 border-danger h3 font-weight-bold text-center bg-primary">News Headline</p>
            <div class="card">
                <div class="card-body shadow">
                    <div id="example2_wrapper">
                        <div class="row">
                            <div class="col-sm-12 col-md-6"></div>
                            <div class="col-sm-12 col-md-6"></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <h2 class="text-dark font-weight-bold">{{ $posts->heading }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body shadow">
                <div id="example2_wrapper">
                    <div class="row">
                        <div class="col-sm-12 col-md-6"></div>
                        <div class="col-sm-12 col-md-6"></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-8">
                            <p>{!! $posts->content !!}</p>
                        </div>
                        <div class="col-sm-4" style="max-height:700px; overflow-y:scroll">
                            <h4 class="text-primary font-weight-bold bg-dark">All Stories</h4>
                            @foreach($allPost as $value)
                            <div class="card mb-2">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <img src="{{ $value->image }}" style="height:291px; width:100%" alt="image">
                                        <span class="text-success font-weight-bold"><span class="text-dark">Created By:</span> {{ $posts->created_by }}</span><br>
                                        <span class="text-success font-weight-bold"><span class="text-dark">Published:</span> {{ $posts->created_at }}</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
</div>

<script type="text/javascript">
</script>
@endsection