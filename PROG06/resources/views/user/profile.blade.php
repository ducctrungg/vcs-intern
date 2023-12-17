@extends('layouts.app')

@section('content')
  <div class="d-flex align-items-center h-100">
    <div class="container" style="height: 500px">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="mb-4">
              <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('storage/avatars/default.jpg') }}"
                class="rounded img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
            </div>
            <p><strong>Họ và tên: </strong>
              {{ $user->fullname }}
            </p>
            <p><strong>Vai trò: </strong>
              @if ($user->role == 'student')
                Học sinh
              @else
                Giáo viên
              @endif
            </p>
            <p><strong>Email: </strong>
              {{ $user->email }}
            </p>
            <p><strong>Số điện thoại: </strong>
              {{ $user->phone }}
            </p>
            <p><strong>Website: </strong>
              {{ $user->website }}
            </p>
            <p><strong>Miêu tả: </strong>
              {{ $user->description }}
            </p>
            @if (($user->role == 'student' && Auth::user()->role == 'teacher') || (Auth::user()->id == $user->id))
              <a href="/users/edit/{{ $user->id }}" class="btn btn-primary">Sửa thông tin</a>
            @endif
          </div>
        </div>
      </div>
    </div>

  </div>
@endsection
