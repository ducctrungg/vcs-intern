@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row mt-5">
      <div class="col-md-8 offset-md-2">
        <h2 class="mb-4">Danh sách người dùng</h2>
        <table class="table table-hover table-dark">
          <thead>
            <tr>
              <th scope="col">Tên</th>
              <th scope="col">Vai trò</th>
              <th scope="col">Chi tiết</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($users as $user)
              <tr>
                <td>{{ $user->fullname }}</td>
                <td>{{ $user->role == 'teacher' ? 'Giáo viên' : 'Học Sinh' }}</td>
                <td>
                  <a href="/users/{{ $user->id }}" class="btn btn-primary btn-sm">Xem chi tiết</a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection
