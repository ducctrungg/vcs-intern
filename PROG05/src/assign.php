<?php
require_once __DIR__ . "/bootstrap.php";

function submit_assignment($student_id, $assignment_id, $inputs)
{
  global $conn;
  connect_db();
  foreach ($inputs as $key => $value) {
    $data[] = $value;
  }
  $sql = "INSERT INTO submission (student_id, assignment_id, submission_description, submission_path) VALUES ('$student_id', '$assignment_id', '$data[0]', '$data[1]')";
  $result = mysqli_query($conn, $sql);
  return $result;
}

$is_submit = function ($student_id, $assignment_id): bool {
  global $conn;
  connect_db();
  $sql = "select * from submission where student_id = '$student_id' and assignment_id = '$assignment_id'";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
  if (mysqli_num_rows($result) > 0) {
    return true;
  }
  return false;
};

function student_submit()
{
  $list = array();
  $result = get_detail_column_id("submission", 'assignment_id', $_SESSION['get_id']);
  while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    $list[] = array_merge($row, get_detail_from_id('user', $row['student_id']));
  }
  return $list;
}

function update_score($score, $student_id, $assignment_id)
{
  global $conn;
  connect_db();
  $sql = "UPDATE submission SET grade = '$score', is_grade = '1' WHERE student_id = $student_id AND assignment_id = $assignment_id";
  $result = mysqli_query($conn, $sql);
  return $result;
}
// $students_submit = get_detail_column_id("submission", 'assignment_id', $_SESSION['get_id']);

if (is_post_request()) {
  if (isset($_POST['action'])) {
    // [$inputs, $errors] = filter($_POST, [
    //   'score' => 'int | required',
    // ]);
    // if ($errors) {
    //   echo "False";
    //   exit;
    // }
    if (update_score($_POST['score'], $_POST['student_id'], $_POST['assignment_id'])) {
      echo "True";
    } else {
      echo "False";
    }
    exit;
  } else {
    [$inputs, $errors] = filter($_POST, [
      'submission_description' => 'string',
    ]);
    check_file_valid($inputs, $errors, 'submission_path');

    if ($errors) {
      redirect_with(htmlspecialchars($_SERVER['PHP_SELF']), [
        'errors' => $errors,
        'inputs' => $inputs
      ]);
    }

    if (!submit_assignment($_SESSION['id'], $_SESSION['get_id'], $inputs)) {
      redirect_with(htmlspecialchars($_SERVER['PHP_SELF']), [
        'errors' => $errors,
        'inputs' => $inputs
      ]);
    }
    // submit successfully
    redirect_to('course.php');
  }
  // fn() => isset($_GET['id']) ? $_GET['id'] : $_SESSION['get_id']
} elseif (is_get_request() && isset($_GET['id'])) {
  $_SESSION['get_id'] = $_GET['id'];
  $list_submit = student_submit();
  $info = get_detail_from_id("assignment", $_GET['id']);
} elseif (is_get_request()) {
  $info = get_detail_from_id("assignment", $_SESSION['get_id']);
  [$errors, $inputs] = session_flash('errors', 'inputs');
}
?>

<?php view('header', ['title' => $info['title']]) ?>
<!-- Hiển thị thông tin bài tập cho sinh viên -->
<div class="mb-4">
  <h3>Thông tin bài tập</h3>
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Bài tập:
        <?= $info['title'] ?>
      </h5>
      <p class="card-text">
      <p>Mô tả:
        <?= $info['description'] ?>
      </p>
      <?php if ($_SESSION['role'] == 'student'): ?>
        <p class="<?= $is_submit($_SESSION['id'], $_SESSION['get_id']) ? "text-success" : "text-danger" ?>"><strong>
            Trạng thái:
            <?= $is_submit($_SESSION['id'], $_SESSION['get_id']) ? "Đã nộp" : "Chưa nộp" ?>
          </strong></p>
        </p>
      <?php endif ?>
      <a href=" <?= $info['assignment_path'] ?>" class="btn btn-primary">Tải Bài Tập</a>
    </div>
  </div>
</div>

<?php if ($_SESSION['role'] == 'student'): ?>
  <h3>Nộp bài tập</h3>
  <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" enctype="multipart/form-data">
    <div class="mb-3">
      <label for="submission_path" class="form-label">Chọn File Bài Làm:</label>
      <input type="file" class="form-control" id="submission_path" name="submission_path">
    </div>
    <strong class="text-danger text-end mt-2">
      <?= isset($errors['submission_path']) ? $errors['submission_path'] : '' ?>
    </strong>
    <div class="mb-3">
      <label for="description" class="form-label">Ghi chú (Tối đa 1000 kí tự)</label>
      <textarea class="form-control" name="description" id="description" cols="30" rows="5"></textarea>
    </div>
    <p><strong class="text-danger text-end mt-2">
        <?= isset($errors['submission_description']) ? $errors['submission_description'] : '' ?>
      </strong></p>
    <button type="submit" class="btn btn-primary">Gửi Bài Làm</button>
  </form>
<?php else: ?>
  <div class="mt-4">
    <h2>Danh sách học sinh nộp bài</h2>
    <?php if ($list_submit): ?>
      <ul class="list-group">
        <?php foreach ($list_submit as $key): ?>
          <li class="list-group-item list-group-item-action">
            <strong>
              <?= $key['full_name'] ?>
            </strong>
            <a href="<?= $key['submission_path'] ?>" class="btn btn-link float-end">Tải Bài Làm</a>
            <?php if (!isset($key['is_grade'])): ?>
              <button type="button" class="btn btn-primary float-end me-2" data-bs-toggle="modal" data-bs-target="#gradeModal"
                data-bs-student-name="<?= $key['full_name'] ?>" data-bs-student-id="<?= $key['id'] ?>">Chấm Điểm</button>
            <?php else: ?>
              <p class="fw-bold float-end me-2 ">Điểm đã chấm:
                <?= $key['grade'] ?>
              </p>
            <?php endif ?>
          </li>
        <?php endforeach ?>
      </ul>
    <?php endif ?>
  </div>
  <div class="modal fade" id="gradeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h6 class="modal-title text-dark fw-bold">Chấm Điểm Bài Làm</h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST" id="btn_grade_score">
          <div class="modal-body">
            <p class="text-dark">Điểm Bài Làm:</p>
            <input type="hidden" value="" id="student_id" name="student_id">
            <input type="hidden" value="<?= $_SESSION['get_id'] ?>" id="assignment_id" name="assignment_id">
            <input type="number" class="form-control" id="grade" name="grade" min="0" max="10" required>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            <button type="submit" class="btn btn-primary" id="grade_score">Lưu Điểm</button>
          </div>
        </form>
      </div>
    </div>
  </div>
<?php endif ?>
<?php view('footer') ?>
<script>
  $().ready(() => {
    const showModal = document.getElementById('gradeModal')
    if (showModal) {
      showModal.addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget
        const recipient = button.getAttribute('data-bs-student-name')
        const send_id = button.getAttribute('data-bs-student-id')
        // Update the modal's content.
        $('.modal-title').text(`Chấm điểm cho ${recipient}`)
        $('#student_id').val(send_id)
      })
    }
    $("#btn_grade_score").on("submit", function (event) {
      event.preventDefault();
      let score = parseInt($("#grade").val());
      let student_id = parseInt($("#student_id").val());
      let assignment_id = parseInt($("#assignment_id").val());
      let data_send = {
        action: "grade",
        score: score,
        student_id: student_id,
        assignment_id: assignment_id,
      }
      $("#grade").val('');
      $.ajax({
        url: "assign.php",
        method: "POST",
        data: data_send,
      })
        .done(function (response) {
          alert("Cập nhật điểm thành công")
        })
    })
    $(document).ajaxStop(function () {
      window.location.reload();
    });
  })
</script>