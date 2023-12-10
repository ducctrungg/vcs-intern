<?php
require_once __DIR__ . "/../src/bootstrap.php";
require_once __DIR__ . "/../src/login.php";
?>

<?php if (isset($errors['login'])): ?>
  <div class="alert alert-error">
    <?= $errors['login'] ?>
  </div>
<?php endif ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
    crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
  <title>
    <?= $title ?? 'Trang chủ' ?>
  </title>
</head>

<body class="text-bg-dark" style="height: 100vh">
  <div class="d-flex align-items-center h-100">
    <div class="container-md form-signin" style="max-width: 25rem">
      <h1 class="h3 mb-3 fw-bold">Login</h1>
      <form method="POST" action="../src/login.php">
        <div class="mb-3">
          <label for="username" class="form-label">Tên đăng nhập</label>
          <input type="text" class="form-control" id="username" name="username">
          <small class="text-danger">
            <?php echo isset($errors['username']) ? $errors['username'] : '' ?>
          </small>
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Mật khẩu</label>
          <input type="password" class="form-control" id="password" name="password">
          <small class="text-danger">
            <?php echo isset($errors['password']) ? $errors['password'] : '' ?>
          </small>
        </div>
        <div class="form-check text-start my-3">
          <label class="form-check-label" for="checkRemember">
            Remember me
          </label>
          <input class="form-check-input" type="checkbox" value="remember-me" id="checkRemember">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
      </form>
    </div>
  </div>
</body>

</html>