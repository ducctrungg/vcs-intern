<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">

  <title>
    <?= $title ?? 'Trang chủ' ?>
  </title>
</head>

<body class="text-bg-dark" style="height: 100vh">
  <div class="container d-flex w-100 h-100 mx-auto flex-column">
    <header class="d-flex align-items-center justify-content-between py-2 mb-3 border-bottom text-center">
      <ul class="nav col-12 col-md-auto justify-content-center mb-md-0">
        <li><a href="/index.php" class="nav-link px-2">Trang chủ</a></li>
      </ul>
      <div class="col-md-3 text-end">
        <span class="pe-3 fw-bold">
          <?= $_SESSION['full_name'] ?>
        </span>
        <img src="<?= isset($_SESSION['avatar_path']) ? $_SESSION['avatar_path'] : '/../imgs/default.png' ?>"
          class="rounded img-thumbnail" alt="..." style="width: 30px; height: 30px">
      </div>
    </header>