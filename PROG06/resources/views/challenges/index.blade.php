@extends('layouts.app')

@section('content')
  <div class="container mt-5">
    <h2 class="text-center">Trò Chơi Giải Đố</h2>
    @if (Auth::user()->role == 'teacher')
      <div class="mb-4">
        <h2>Tạo Challenge</h2>
        <form action="/challenges" method="post" enctype="multipart/form-data">
          @csrf
          <div class="mb-3">
            <label for="hint" class="form-label">Gợi ý:</label>
            <textarea class="form-control" id="hint" name="hint" rows="3">{{ $challenge ? $challenge->hint : '' }}</textarea>
          </div>
          @error('hint')
            <strong class="text-danger text-end mt-2">
              {{ $message }}
            </strong>
          @enderror
          @if ($challenge)
            <div class="mb-3">
              <label for="challenge" class="form-label">Upload File Challenge:</label>
              <span class="text-danger fw-bold">
                {{ basename($challenge->challenge) }}
              </span>
            </div>
          @else
            <div class="mb-3">
              <label for="challenge" class="form-label">Upload File Challenge:</label>
              <input type="file" class="form-control" id="challenge" name="challenge">
            </div>
            @error('challenge')
              <p class="text-danger text-end mt-2 fw-bold">
                {{ $message }}
              </p>
            @enderror
            <button type="submit" class="btn btn-primary">Tạo Challenge</button>
          @endif
        </form>
        @if ($challenge)
          <form action='/challenges/{{ $challenge->id }}' method="POST">
            @method('DELETE')
            @csrf
            <button type="submit" class="btn btn-danger">Xóa challenge</button>
          </form>
        @endif
      </div>
    @else
      <div>
        <h2>Tham Gia Challenge</h2>
        <p>Gợi ý Challenge: <span id="challengeHintText">
            {{ $challenge ? $challenge->hint : 'Không có challenge nào được đăng' }}
          </span></p>
        <form action="/challenges/answer" method="post" id="submit_challenge">
          @csrf
          <div class="mb-3">
            <label for="answer" class="form-label">Nhập đáp án:</label>
            <input type="text" class="form-control" id="answer" name="answer">
          </div>
          @error('answer')
            <p class="text-danger mt-2 fw-bold">
              {{ $message }}
            </p>
          @enderror
          <button type="submit" class="btn btn-success" data-bs-target="#challengeModal" data-bs-toggle="modal">Kiểm tra
            đáp
            án</button>
        </form>
      </div>
    @endif
    @if (Session::has('result'))
     <p class="mt-3 fw-bold">{{ Session::get('result') }}</p> 
      @if (Session::has('content'))
        <p>{{Session::get('content')}}</p>
      @endif
    @endif
    {{-- <div class="modal fade" id="challengeModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-dark" id="modalResult">Chúc mừng bạn đã trả lời đúng</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p class="text-dark">Nội dung trong file đáp án</p>
            <p class="text-dark"></p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
          </div>
        </div>
      </div>
    </div> --}}
  </div>
  <script>
    let request;
    $("#submit_challenge").submit(event => {
      var $form = $(this);
      var $inputs = $form.find("input, button");
      $inputs.prop("disabled", true);
    })
    // var fileName = $(this).val().split("\\").pop();
    // $('#challengeHintText').text("Gợi Ý từ File: " + fileName);

    // Đọc nội dung từ file (cần xác nhận server hỗ trợ đọc file)
    // $.get(fileName, function (data) {
    //     console.log(data);
    // });
  </script>
@endsection
