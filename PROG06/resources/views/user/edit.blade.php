@extends('layouts.app')

@section('content')
  <div class="d-flex align-items-center h-100">
    <div class="container-md form-signin">
      <h1 class="h3 mb-3 fw-bold">Thay đổi thông tin cá nhân</h1>
      <form method="POST" enctype="multipart/form-data" action="/users/{{ $user->id }}">
        @csrf
        @method('PUT')
        <div class="row mb-3">
          <label for="username" class="col-sm-2 col-form-label">Tên đăng nhập</label>
          <div class="col-sm-10">
            <input type="text" class="form-control-plaintext text-light" id="username" name="username"
              value="{{ $user->username }}" readonly>
          </div>
        </div>
        <div class="row mb-3">
          <label for="full_name" class="col-sm-2 col-form-label">Họ và tên</label>
          <div class="col-sm-10">
            <input type="text" class="form-control-plaintext text-light" id="full_name" name="full_name"
              value="{{ $user->fullname }}" readonly>
          </div>
        </div>
        <div class="row mb-3">
          <label for="avatar" class="col-sm-2 col-form-label">Upload file hình ảnh:</label>
          <div class="col-sm-10">
            <input type="file" id="avatar" name="avatar" />
          </div>
          @error('avatar')
          <strong class="text-danger text-end mt-2">
            {{ $message }}
          </strong>
        @enderror
        </div>
        <div class="row mb-3">
          <label for="email" class="col-sm-2 col-form-label">Email</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="email" name="email" value="{{ $user->email }}">
          </div>
          @error('email')
            <strong class="text-danger text-end mt-2">
              {{ $message }}
            </strong>
          @enderror
        </div>
        <div class="row mb-3">
          <label for="phone" class="col-sm-2 col-form-label">Số điện thoại</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="phone" name="phone" value="{{ $user->phone }}">
          </div>
          @error('phone')
            <strong class="text-danger text-end mt-2">
              {{ $message }}
            </strong>
          @enderror
        </div>
        <div class="row mb-3">
          <label for="website" class="col-sm-2 col-form-label">URL trang cá nhân</label>
          <div class="col-sm-10">
            <input type="url" class="form-control" id="website" name="website" value="{{ $user->website }}">
          </div>
          @error('website')
            <strong class="text-danger text-end mt-2">
              {{ $message }}
            </strong>
          @enderror
        </div>
        <div class="row mb-3">
          <label for="description" class="col-sm-2 col-form-label">Mô tả (Tối đa 1000 kí tự)</label>
          <div class="col-sm-10">
            <textarea class="form-control" name="description" id="description" cols="30" rows="5">{{ $user->description }}</textarea>
            @error('description')
              <strong class="text-danger text-end mt-2">
                {{ $message }}
              </strong>
            @enderror
          </div>
        </div>
        <button type="submit" class="btn btn-primary float-end">Lưu thông tin</button>
      </form>
    </div>
  </div>
@endsection
