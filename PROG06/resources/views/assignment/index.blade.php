@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="container mt-3">
      <div class="my-3">
        <h3 class="mb-3">Danh sách bài tập</h3>
        <div class="list-group mb-4">
          @foreach ($lists as $list)
            <li class="list-group-item list-group-item-action" style="cursor: pointer">
              <a href="/assignment/{{ $list->id }}/submission">
                <span class="fw-bold">
                  {{ $list->title }}
                </span>
                <a href="{{ 'storage/' . $list->assignment }}" class="btn btn-link float-end">Link</a>
                <p class="mb-1">
                  {{ $list->description }}
                </p>
              </a>
            </li>
          @endforeach
        </div>
      </div>
      @if (Auth::user()->role == 'teacher')
        <div class="mb-4">
          <h3>Giao bài tập</h3>
          <form action="/assignment" method="post" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
              <label for="title" class="form-label">Tiêu đề bài tập</label>
              <input type="text" class="form-control" id="title" name="title">
            </div>
            @error('title')
              <strong class="text-danger">
                {{ $message }}
              </strong>
            @enderror
            <div class="mb-3">
              <label for="description" class="form-label">Mô tả (Tối đa 1000 kí tự)</label>
              <textarea class="form-control" name="description" id="description" cols="30" rows="5"></textarea>
            </div>
            @error('description')
              <strong class="text-danger">
                {{ $message }}
              </strong>
            @enderror
            <div class="mb-3">
              <label for="assignment" class="form-label">Chọn file bài tập</label>
              <input type="file" class="form-control" id="assignment" name="assignment">
            </div>
            @error('assignment')
              <p class="fw-bold text-danger">
                {{ $message }}
              </p>
            @enderror
            <button type="submit" class="btn btn-primary">Giao bài tập</button>
          </form>
        </div>
      @endif
    </div>
  </div>
@endsection
