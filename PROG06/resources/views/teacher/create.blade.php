@extends('layouts.app')

@section('content')
  <div class="row justify-content-center mt-5">
    <div class="col-md-8">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Đăng ký thông tin học viên</h4>
          <form method="POST" action="/teacher/create">
            @csrf
            <div class="mb-3">
              <label for="username" class="form-label">Tên đăng nhập</label>
              <input type="text" class="form-control" id="username" name="username" value="{{old('username')}}" placeholder="Nhập tên đăng nhập">
              @error('username')
                <p class="text-danger mt-2">{{ $message }}</p>
              @enderror
            </div>
            <div class="mb-3">
              <label for="fullname" class="form-label">Họ và tên</label>
              <input type="text" class="form-control" id="fullname" name="fullname" value="{{old('fullname')}}" placeholder="Nhập họ và tên">
              @error('fullname')
                <p class="text-danger mt-2">{{ $message }}</p>
              @enderror
            </div>
            <div class="mb-3">
              <label for="phone" class="form-label">Số điện thoại</label>
              <input type="tel" class="form-control" id="phone" name="phone" value="{{old('phone')}}" placeholder="Nhập số điện thoại">
              @error('phone')
                <p class="text-danger mt-2">{{ $message }}</p>
              @enderror
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Địa chỉ email</label>
              <input type="email" class="form-control" id="email" name="email" value="{{old('email')}}" placeholder="Nhập địa chỉ email">
              @error('email')
                <p class="text-danger mt-2">{{ $message }}</p>
              @enderror
            </div>
            <button type="submit" class="btn btn-primary">Đăng ký</button>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
