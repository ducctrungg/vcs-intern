@extends('layouts.app')
@section('content')
  <div class="row">
    <x-card title="Trang cá nhân" description="Xem thông tin người dùng" link="/users/{{Auth::user()->id}}"/>
    <x-card title="Người dùng" description="Danh sách người dùng trong hệ thống" link="/users" />
    <x-card title="Lớp học" description="Quản lí bài tập" link="/assignment" />
    <x-card title="Trò chơi" description="Trò chơi đố vui" link="/challenges"/>
    <x-card title="Đoạn chat" description="Mục tin nhắn" link="/chat"/>
    <x-card title="Học sinh" description="Quản lí học sinh" link="/teacher/create" />
  </div>
@endsection
