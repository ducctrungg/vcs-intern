<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Xử lý upload avatar từ file hoặc URL
    if ($_FILES['avatar_file']['error'] === UPLOAD_ERR_OK) {
        $avatarPath = 'uploads/' . basename($_FILES['avatar_file']['name']);
        move_uploaded_file($_FILES['avatar_file']['tmp_name'], $avatarPath);
    } elseif (!empty($_POST['avatar_url'])) {
        $avatarPath = $_POST['avatar_url'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thay Đổi Avatar</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h1 class="text-center mb-4">Thay Đổi Avatar</h1>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <!-- Form Thay Đổi Avatar -->
            <form method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="avatar_file" class="form-label">Chọn Avatar từ File:</label>
                    <input type="file" class="form-control" id="avatar_file" name="avatar_file">
                </div>
                <div class="mb-3">
                    <label for="avatar_url" class="form-label">Hoặc nhập URL Avatar:</label>
                    <input type="text" class="form-control" id="avatar_url" name="avatar_url">
                </div>
                <button type="submit" class="btn btn-primary">Lưu Avatar</button>
            </form>

            <!-- Hiển thị Avatar -->
            <?php if (isset($avatarPath)): ?>
                <div class="mt-4">
                    <h2 class="h5">Avatar Hiện Tại:</h2>
                    <img src="<?= $avatarPath ?>" class="img-fluid rounded" alt="Avatar">
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Bootstrap JS and Popper.js (optional) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
</body>
</html>
