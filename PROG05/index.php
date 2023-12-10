<?php
require __DIR__ . '/src/bootstrap.php';
require_login();
?>

<?php view('header') ?>

<main>
  <div class="row">
    <div class="col-sm-6 col-md-4 mb-3">
      <div class="card my-3 text-bg-secondary">
        <div class="card-body">
          <h5 class="card-title">Trang cá nhân</h5>
          <p class="card-text">Thông tin cá nhân của người dùng</p>
          <a href="/src/dashboard.php" class="btn btn-primary">Truy cập</a>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-md-4 mb-3">
      <div class="card my-3 text-bg-secondary">
        <div class="card-body">
          <h5 class="card-title">Lớp học</h5>
          <p class="card-text">Quản lí bài tập</p>
          <a href="/src/course.php" class="btn btn-primary">Truy cập</a>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-md-4 mb-3">
      <div class="card my-3 text-bg-secondary">
        <div class="card-body">
          <h5 class="card-title">Trò chơi</h5>
          <p class="card-text">Trò chơi giải đố</p>
          <a href="/src/challenges.php" class="btn btn-primary">Truy cập</a>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-md-4 mb-3">
      <div class="card my-3 text-bg-secondary">
        <div class="card-body">
          <h5 class="card-title">Đoạn chat</h5>
          <p class="card-text">Mục tin nhắn</p>
          <a href="/src/chatroom.php" class="btn btn-primary">Truy cập</a>
        </div>
      </div>
    </div>
  </div>
</main>

<?php view('footer') ?>