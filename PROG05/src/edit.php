<?php
require_once __DIR__ . "/bootstrap.php";
require_once __DIR__ . "/libs/upload.php";

$inputs = [];
$errors = [];
if (is_post_request()) {
  [$inputs, $errors] = filter($_POST, [
    'email' => 'email | email',
    'phone' => 'string | min:10, max:10',
    'url' => 'url',
    'description' => 'string'
  ]);
  check_file_valid($inputs, $errors, 'avatar_path');
  if ($errors) {
    redirect_with(htmlspecialchars($_SERVER['PHP_SELF']), ['errors' => $errors, 'inputs' => $inputs]);
  }

  // if update fails
  if (!update_db('user', $inputs, $_SESSION['id'])) {
    redirect_with(htmlspecialchars($_SERVER['PHP_SELF']), [
      'errors' => $errors,
      'inputs' => $inputs
    ]);
  }
  // Update success
  redirect_with_message('dashboard.php', 'update', 'Update information successful');
} else {
  [$errors, $inputs] = session_flash('errors', 'inputs');
  $data = get_detail_user("user", $_SESSION['username']);
}
?>

<?php view('header', ['title' => 'Thay đổi thông tin cá nhân']) ?>
<div class="d-flex align-items-center h-100">
  <div class="container-md form-signin">
    <h1 class="h3 mb-3 fw-bold">Thay đổi thông tin cá nhân</h1>

    <form method="POST" enctype="multipart/form-data" action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>">
      <div class="row mb-3">
        <label for="username" class="col-sm-2 col-form-label">Tên đăng nhập</label>
        <div class="col-sm-10">
          <input type="text" class="form-control-plaintext text-light" id="username" name="username"
            value=<?= $data['username'] ?> readonly>
        </div>
      </div>
      <div class="row mb-3">
        <label for="full_name" class="col-sm-2 col-form-label">Họ và tên</label>
        <div class="col-sm-10">
          <input type="text" class="form-control-plaintext text-light" id="full_name" name="full_name"
            value=<?= $data['full_name'] ?> readonly>
        </div>
      </div>
      <div class="row mb-3">
        <label for="avatar_path" class="col-sm-2 col-form-label">Upload file hình ảnh:</label>
        <div class="col-sm-10">
          <input type="file" id="avatar_path" name="avatar_path" />
        </div>
        <strong class="text-danger text-end mt-2">
          <?= isset($errors['avatar_path']) ? $errors['avatar_path'] : '' ?>
        </strong>
      </div>
      <div class="row mb-3">
        <label for="email" class="col-sm-2 col-form-label">Email</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="email" name="email" value=<?= $data['email'] ?>>
        </div>
        <strong class="text-danger text-end mt-2">
          <?= isset($errors['email']) ? $errors['email'] : '' ?>
        </strong>
      </div>
      <div class="row mb-3">
        <label for="phone" class="col-sm-2 col-form-label">Số điện thoại</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="phone" name="phone" value=<?= $data['phone'] ?>>
        </div>
        <strong class="text-danger text-end mt-2">
          <?= isset($errors['phone']) ? $errors['phone'] : '' ?>
        </strong>
      </div>
      <div class="row mb-3">
        <label for="url" class="col-sm-2 col-form-label">URL trang cá nhân</label>
        <div class="col-sm-10">
          <input type="url" class="form-control" id="url" name="url" value=<?= $data['url'] ?>>
        </div>
        <strong class="text-danger text-end mt-2">
          <?= isset($errors['url']) ? $errors['url'] : '' ?>
        </strong>
      </div>
      <div class="row mb-3">
        <label for="description" class="col-sm-2 col-form-label">Mô tả (Tối đa 1000 kí tự)</label>
        <div class="col-sm-10">
          <textarea class="form-control" name="description" id="description" cols="30"
            rows="5"><?= $data['description'] ?></textarea>
          <strong class="text-danger text-end mt-2">
            <?= isset($errors['description']) ? $errors['description'] : '' ?>
          </strong>
        </div>
      </div>
      <button type="submit" class="btn btn-primary text-end">Lưu thông tin</button>
    </form>

  </div>
</div>
<?php view('footer') ?>