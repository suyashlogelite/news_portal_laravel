@extends('layouts.layout2')
@push('title')
<title>login-news-portal</title>
@endpush

@if (session('success'))
<div class="alert alert-danger" role="alert">
    {{ session('success') }}
</div>
@endif

@section('form')
<main class="login-form">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5" style="margin-top:20%;">
                <div class="card shadow">
                    <div class="card-header shadow bg-success h2 text-center"><span class="text-dark font-weight-bold">Login Portal</span></div>
                    <div class="card-body">
                        <form action="{{ route('login.post') }}" method="POST">
                            @csrf
                            <div class="form-group row">
                                <label for="email_address" class="col-md-4 col-form-label text-md-right">E-Mail</label>
                                <div class="col-md-6">
                                    <input type="text" id="email_address" class="form-control" name="email" required
                                        autofocus>
                                    @if ($errors->has('email'))
                                    <span class="text-danger">{{ $errors->first('email') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>
                                <div class="col-md-6">
                                    <input type="password" id="password" class="form-control" name="password" required>
                                    @if ($errors->has('password'))
                                    <span class="text-danger">{{ $errors->first('password') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6 offset-md-4">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="remember"> Remember Me
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary text-dark font-weight-bold form-control">
                                    <span class="font-weight-bold text-white">Login</span> 
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection