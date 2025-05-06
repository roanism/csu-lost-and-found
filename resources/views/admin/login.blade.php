@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <h2 class="csu-header mb-4">Admin Login</h2>
        <form method="POST" action="{{ route('admin.login.submit') }}">
            @csrf
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required autofocus>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            @if($errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif
            <button type="submit" class="btn btn-csu">Login</button>
        </form>
    </div>
</div>
@endsection