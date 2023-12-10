<?php
require_once __DIR__ . "/bootstrap.php";
require_login();
$info = get_detail_user("user", $_SESSION["username"]);
$result = get_all_table_db("user");
if (is_get_request() && isset($_GET["id"])) {
  $info = get_detail_from_id("user", $_GET['id']);
}
?>

<?php if (isset($_SESSION[FLASH]['update'])): ?>
  <div class="alert alert-error">
    Update information successful
  </div>
<?php endif ?>

<?php view('header', ['title' => 'Trang cá nhân']) ?>
<div class="d-flex align-items-center h-100">
  <div class="container">
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-lg-8">
            <div class="mb-4">
              <img src="<?= isset($info['avatar_path']) ? $info['avatar_path'] : '/../imgs/default.png' ?>"
                class="rounded img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
            </div>
            <p><strong>Họ và tên: </strong>
              <?= $info['full_name'] ?>
            </p>
            <p><strong>Vai trò: </strong>
              <?= $info['role'] == 'teacher' ? 'Giáo viên' : 'Học sinh' ?>
            </p>
            <p><strong>Email: </strong>
              <?= $info['email'] ?>
            </p>
            <p><strong>Số điện thoại: </strong>
              <?= $info['phone'] ?>
            </p>
            <p><strong>Miêu tả: </strong>
              <?= $info['description'] ?>
            </p>
            <?php if ($_SESSION['id'] == ($info["id"])): ?>
              <a href="edit.php" class="btn btn-primary">Sửa thông tin</a>
            <?php elseif (($_SESSION['role'] == "teacher") && ($info['role'] == 'student')): ?>
              <a href="edit.php" class="btn btn-primary">Sửa thông tin</a>
            <?php elseif (isset($_GET["id"]) && $_SESSION['id'] == ($_GET["id"])): ?>
              <a href="edit.php" class="btn btn-primary">Sửa thông tin</a>
            <?php endif ?>
            <?php if ($_SESSION['id'] != ($info["id"])): ?>
              <a type="button" class="btn btn-success" href="chatroom.php">
                <i class="bi bi-chat-fill"></i>
                Gửi tin nhắn
            </a>
            <?php endif ?>

          </div>
          <div class="col-lg-4 overflow-y-scroll border-start border-2" style="min-height: 500px">
            <p class="fw-bold">Danh sách học viên và giáo viên</p>
            <ul class="list-group list-group-flush">
              <?php foreach ($result as $key): ?>
                <a class="list-group-item d-flex justify-content-start align-items-center list-group-item-action"
                  href="dashboard.php?id=<?= $key['id'] ?>" style="cursor: pointer">
                  <img src="<?= isset($key['avatar_path']) ? $key['avatar_path'] : '/../imgs/default.png' ?>" alt="avatar"
                    width="30" height="30" style="object-fit: cover" class="rounded-circle">
                  <p class="ms-3 mb-0 align-middle">
                    <?= $key['full_name'] ?>
                  </p>
                </a>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>

</body>

</html>