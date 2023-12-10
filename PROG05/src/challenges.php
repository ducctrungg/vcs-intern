<?php
require_once __DIR__ . "/bootstrap.php";
require_login();
$have_challenge = false;
if (have_row("challenges")) {
  $have_challenge = true;
  $tmp = get_all_table_db("challenges");
  $data = mysqli_fetch_array($tmp);
}
function challenge_submit($teacher_id, $inputs)
{
  global $conn;
  connect_db();
  foreach ($inputs as $key => $value) {
    $data[] = $value;
  }
  $sql = "INSERT INTO challenges (teacher_id, challenge_hint, challenge_path) VALUES ('$teacher_id', '$data[0]', '$data[1]')";
  $result = mysqli_query($conn, $sql);
  return $result;
}

$inputs = [];
$errors = [];
if (is_post_request()) {
  [$inputs, $errors] = filter($_POST, [
    'challenge_hint' => 'string | required'
  ]);
  check_file_valid($inputs, $errors, 'challenge_path');
  if ($errors) {
    redirect_with(htmlspecialchars($_SERVER['PHP_SELF']), [
      'errors' => $errors,
      'inputs' => $inputs
    ]);
  }

  // if update fails
  if (!challenge_submit($_SESSION['id'], $inputs)) {
    redirect_with(htmlspecialchars($_SERVER['PHP_SELF']), [
      'errors' => $errors,
      'inputs' => $inputs
    ]);
  }
  // Update success
  redirect_to(htmlspecialchars($_SERVER['PHP_SELF']));
} else {
  if (isset($_GET['answer'])) {
    [$inputs, $errors] = filter($_GET, [
      'answer' => 'string | required'
    ]);
    if ($errors) {
      redirect_with(htmlspecialchars($_SERVER['PHP_SELF']), [
        'errors' => $errors,
        'inputs' => $inputs
      ]);
    }
  }
  [$errors, $inputs] = session_flash('errors', 'inputs');
}
?>

<?php view('header', ['title' => 'Trò chơi giải đố']) ?>

<div class="container mt-5">
  <h2 class="text-center">Trò Chơi Giải Đố</h2>
  <!-- Form tạo Challenge (Chỉ giáo viên mới thấy) -->

  <?php if ($_SESSION['role'] == 'teacher'): ?>
    <div class="mb-4">
      <h2>Tạo Challenge</h2>
      <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" enctype="multipart/form-data">
        <div class="mb-3">
          <label for="challenge_hint" class="form-label">Gợi ý:</label>
          <textarea class="form-control" id="challenge_hint" name="challenge_hint"
            rows="3"><?= isset($data['challenge_hint']) ? $data['challenge_hint'] : '' ?></textarea>
        </div>
        <strong class="text-danger text-end mt-2">
          <?= isset($errors['challenge_hint']) ? $errors['challenge_hint'] : '' ?>
        </strong>
        <?php if ($have_challenge): ?>
          <div class="mb-3">
            <label for="challenge_path" class="form-label">Upload File Challenge:</label>
            <span class="text-danger fw-bold">
              <?= basename($data['challenge_path']) ?>
            </span>
          </div>
        <?php else: ?>
          <div class="mb-3">
            <label for="challenge_path" class="form-label">Upload File Challenge:</label>
            <input type="file" class="form-control" id="challenge_path" name="challenge_path">
          </div>
          <p class="text-danger text-end mt-2 fw-bold">
            <?= isset($errors['challenge_path']) ? $errors['challenge_path'] : '' ?>
          </p>
          <button type="submit" class="btn btn-primary">Tạo Challenge</button>
        <?php endif; ?>
      </form>
      <?php if ($have_challenge): ?>
        <form action='delete.php' method="post">
          <input type="hidden" name="delete" value="<?= $data['id'] ?>">
          <button type="submit" class="btn btn-danger">Xóa challenge</button>
        </form>
      <?php endif; ?>
    </div>
  <?php endif ?>

  <!-- Gợi ý và Form nhập đáp án (Chỉ sinh viên mới thấy) -->
  <?php if ($_SESSION['role'] === 'student'): ?>
    <div>
      <h2>Tham Gia Challenge</h2>
      <p>Gợi ý Challenge: <span id="challengeHintText">
          <?= isset($data) ? $data['challenge_hint'] : "Không có challenge nào được đăng" ?>
        </span></p>
      <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="get" id="submit_challenge">
        <div class="mb-3">
          <label for="answer" class="form-label">Nhập đáp án:</label>
          <input type="text" class="form-control" id="answer" name="answer">
        </div>
        <p class="text-danger mt-2 fw-bold">
          <?= isset($errors['answer']) ? $errors['answer'] : '' ?>
        </p>
        <button type="submit" class="btn btn-success" data-bs-target="#challengeModal" data-bs-toggle="modal">Kiểm tra đáp
          án</button>
      </form>
    </div>
  <?php endif ?>
  <?php if (isset($_GET['answer'])): ?>
    <?php if (trim($_GET['answer']) == pathinfo($data['challenge_path'])['filename']): ?>
      <p>Chúc mừng bạn đã trả lời đúng</p>
    <?php else: ?>
      <p>Rất tiếc bạn đã trả lời sai</p>
    <?php endif ?>
  <?php endif ?>
  <div class="modal fade" id="challengeModal" tabindex="-1" aria-hidden="true">
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
  </div>
</div>
<script>
  let request;
  $("#submit_challenge").submit(event => {
    var $form = $(this);
    // Let's select and cache all the fields
    var $inputs = $form.find("input, button");
    // Serialize the data in the form
    $inputs.prop("disabled", true);
  })
  // var fileName = $(this).val().split("\\").pop();
  // $('#challengeHintText').text("Gợi Ý từ File: " + fileName);

  // Đọc nội dung từ file (cần xác nhận server hỗ trợ đọc file)
  // $.get(fileName, function (data) {
  //     console.log(data);
  // });
</script>
<?php view('footer') ?>