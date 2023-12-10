<?php
require_once __DIR__ . "/bootstrap.php";
require_login();
$result = get_all_table_db("assignment");

if (is_post_request()) {
  [$inputs, $errors] = filter($_POST, [
    'title' => 'string | required',
    'description' => 'string',
  ]);
  check_file_valid($inputs, $errors, 'assignment_path');
  if ($errors) {
    redirect_with(htmlspecialchars($_SERVER['PHP_SELF']), ['errors' => $errors, 'inputs' => $inputs]);
  }

  // if login fails
  if (!add_data_db("assignment", $inputs)) {
    redirect_with(htmlspecialchars($_SERVER['PHP_SELF']), [
      'errors' => $errors,
      'inputs' => $inputs
    ]);
  }
  // login successfully
  redirect_to(htmlspecialchars($_SERVER['PHP_SELF']));
} else if (is_get_request()) {
  [$errors, $inputs] = session_flash('errors', 'inputs');
}
?>

<?php view('header', ['title' => 'Quản lý bài tập']) ?>
<div class="container">
  <div class="container mt-3">
    <div class="my-3">
      <h3 class="mb-3">Danh sách bài tập
        <?= $_SESSION['role'] == 'teacher' ? 'đã đăng' : 'được giao' ?>
      </h3>
      <div class="list-group mb-4">
        <?php foreach ($result as $key): ?>
          <li class="list-group-item list-group-item-action" style="cursor: pointer">
            <a href="assign.php?id=<?= $key['id'] ?>">
              <span class="fw-bold">
                <?= $key['title'] ?>
              </span>
              <a href="<?= $key['assignment_path'] ?>" class="btn btn-link float-end">Link</a>
              <p class="mb-1">
                <?= $key['description'] ?>
              </p>
            </a>
          </li>
        <?php endforeach; ?>
      </div>
    </div>
    <?php if ($_SESSION['role'] == 'teacher'): ?>
      <div class="mb-4">
        <h3>Giao bài tập</h3>
        <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" enctype="multipart/form-data">
          <div class="mb-3">
            <label for="title" class="form-label">Tiêu đề bài tập</label>
            <input type="text" class="form-control" id="title" name="title">
          </div>
          <strong class="text-danger text-end mt-2">
            <?= isset($errors['title']) ? $errors['title'] : '' ?>
          </strong>
          <div class="mb-3">
            <label for="description" class="form-label">Mô tả (Tối đa 1000 kí tự)</label>
            <textarea class="form-control" name="description" id="description" cols="30" rows="5"></textarea>
          </div>
          <div class="mb-3">
            <label for="assignment_path" class="form-label">Chọn file bài tập</label>
            <input type="file" class="form-control" id="assignment_path" name="assignment_path">
          </div>
          <p><strong class="text-danger text-end mt-2">
              <?= isset($errors['assignment_path']) ? $errors['assignment_path'] : '' ?>
            </strong></p>
          <button type="submit" class="btn btn-primary">Giao bài tập</button>
        </form>
      </div>
    <?php endif ?>
  </div>
</div>

<?php view('footer') ?>