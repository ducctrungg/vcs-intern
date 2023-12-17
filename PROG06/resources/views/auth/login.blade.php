<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <title>Đăng nhập</title>
  
</head>

<body class="text-bg-dark" style="height: 100vh">
  <div class="d-flex align-items-center h-100">
    <div class="container-md form-signin" style="max-width: 25rem">
      <h1 class="h3 mb-3 fw-bold">Đăng nhập</h1>
      <form method="POST" action="/users/auth">
        @csrf
        <div class="mb-3">
          <label for="username" class="form-label">Tên đăng nhập</label>
          <input type="text" class="form-control" id="username" name="username">
          @error('username')
            <p class="text-danger">{{ $message }}</p>
          @enderror
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Mật khẩu</label>
          <input type="password" class="form-control" id="password" name="password">
          @error('password')
            <p class="text-danger">{{ $message }}</p>
          @enderror
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
