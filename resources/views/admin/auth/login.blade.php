@extends('admin.layout.auth')
@section('title', 'Login')
@section('content')

<div class="login-box">
    <!-- /.login-logo -->
    <div class="card card-outline card-primary">
      <div class="card-header text-center">
      <a href="{{ url('admin/login') }}" class="h1"><b>Ada</b>shi</a>
      </div>
      <div class="card-body">
        <p class="login-box-msg">Sign in to start your session</p>
          @if (session('error'))
              <div class="alert alert-danger">
                  {{ session('error') }}
              </div>
          @endif
        <form action="{{ route('admin.login') }}" method="post">
          @csrf
          <div class="input-group mb-3">
              <label for="email"> </label>
              <input type="email" name="email" class="form-control" placeholder="Email" required>
              <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
              <label for="password"></label>
              <input type="password" name="password" class="form-control" placeholder="Password" required>
              <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-8">
              <div class="icheck-primary">
                <input type="checkbox" id="remember">
                <label for="remember">
                  Remember Me
                </label>
              </div>
            </div>
            <!-- /.col -->
            <div class="col-4">
              <button type="submit" class="btn btn-primary btn-block">Sign In</button>
            </div>
            <!-- /.col -->
          </div>
        </form>

        <!-- /.social-auth-links -->

        <p class="mb-1">
          <a href="">I forgot my password</a>
        </p>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>
@endsection
