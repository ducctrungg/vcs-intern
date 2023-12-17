@extends('layouts.app')

@section('content')
  @if (Auth::user()->role == 'student')
    <div class="mb-4">
      <h3>Thông tin bài tập</h3>
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Bài tập:
            {{ $assignment->title }}
          </h5>
          <p class="card-text">
          <p>Mô tả:
            {{ $assignment->description }}
          </p>
          @if (Auth::user()->role == 'student')
            <p class="{{ $status->isNotEmpty() ? 'text-success' : 'text-danger' }}"><strong>
                Trạng thái:
                {{ $status->isNotEmpty() ? 'Đã nộp' : 'Chưa nộp' }}
              </strong></p>
            </p>
          @endif
          <a href="{{ asset('storage/' . $assignment->assignment) }}">Tải Bài Tập</a>
        </div>
      </div>
    </div>
    <h3>Nộp bài tập</h3>
    <form action="/assignment/{{ $assignment->id }}/submission" method="post" enctype="multipart/form-data">
      @csrf
      <div class="mb-3">
        <label for="submission" class="form-label">Chọn File Bài Làm:</label>
        <input type="file" class="form-control" id="submission" name="submission">
      </div>
      @error('submission')
        <strong class="text-danger text-end mt-2">
          {{ $message }}
        </strong>
      @enderror
      <div class="mb-3">
        <label for="description" class="form-label">Ghi chú (Tối đa 1000 kí tự)</label>
        <textarea class="form-control" name="description" id="description" cols="30" rows="5"></textarea>
      </div>
      @error('description')
        <strong class="text-danger text-end mt-2">
          {{ $message }}
        </strong>
      @enderror
      <button type="submit" class="btn btn-primary">Gửi Bài Làm</button>
    </form>
  @else
    <div class="mt-4">
      <h2>Danh sách học sinh nộp bài</h2>
      @if ($list_submission)
        <ul class="list-group">
          @foreach ($list_submission as $submission)
            <li class="list-group-item list-group-item-action">
              <strong>
                {{ $submission->fullname }}
              </strong>
              <a href="{{ asset('storage/' . $submission->submission) }}" class="btn btn-link float-end">Tải Bài Làm</a>
              @if ($submission->is_grade)
                <p class="fw-bold float-end me-2 ">Điểm đã chấm:
                  {{ $submission->score }}
                </p>
              @else
                <button type="button" class="btn btn-primary float-end me-2" data-bs-toggle="modal"
                  data-bs-target="#gradeModal" data-bs-student-name="{{ $submission->fullname }}"
                  data-bs-student-id="{{ $submission->user_id }}">Chấm Điểm</button>
              @endif
            </li>
          @endforeach
        </ul>
      @endif
    </div>
    <div class="modal fade" id="gradeModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h6 class="modal-title text-dark fw-bold">Chấm Điểm Bài Làm</h6>
          </div>
          <form action="#" method="POST" id="btn_grade_score">
            <div class="modal-body">
              <p class="text-dark">Điểm Bài Làm:</p>
              <input type="hidden" value="" id="student_id" name="student_id">
              <input type="hidden" value="{{ $submission->assignment_id }}" id="assignment_id" name="assignment_id">
              <input type="number" class="form-control" id="grade" name="grade" min="0" max="10"
                required>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
              <button type="submit" class="btn btn-primary" id="grade_score">Lưu Điểm</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <script>
      $().ready(() => {
        const showModal = document.getElementById('gradeModal')
        if (showModal) {
          showModal.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget
            const recipient = button.getAttribute('data-bs-student-name')
            const send_id = button.getAttribute('data-bs-student-id')
            $('.modal-title').text(`Chấm điểm cho ${recipient}`)
            $('#student_id').val(send_id)
          })
        }
        $("#btn_grade_score").on("submit", function(event) {
          event.preventDefault();
          let score = parseInt($("#grade").val());
          let student_id = parseInt($("#student_id").val());
          let assignment_id = parseInt($("#assignment_id").val());
          let data_send = {
            action: "",
            score: score,
            student_id: student_id,
            assignment_id: assignment_id,
          }
          $("#grade").val('');
          $.ajax({
              url: `/assignment/${assignment_id}/submission/grade`,
              method: "POST",
              data: data_send,
            })
            .done(function(response) {
              alert("Cập nhật điểm thành công")
            })
        })
        $(document).ajaxStop(function() {
          window.location.reload();
        });
      })
    </script>
  @endif

@endsection
