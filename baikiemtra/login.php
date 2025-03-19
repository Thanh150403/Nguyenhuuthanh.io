<?php
session_start();
require_once 'database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $masv = $_POST['masv'];

    // Check if student exists
    $stmt = $conn->prepare("SELECT * FROM SinhVien WHERE MaSV = :masv");
    $stmt->bindParam(':masv', $masv);
    $stmt->execute();
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($student) {
        $_SESSION['masv'] = $student['MaSV'];
        $_SESSION['hoten'] = $student['HoTen'];
        header("Location: hocphan.php");
        exit();
    } else {
        $error = 'Mã sinh viên không tồn tại!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .login-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(30, 141, 189, 0.1);
        }

        .back-link {
            margin-top: 15px;
            display: block;
            text-align: center;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">QUẢN LÝ SINH VIÊN</a>
            <div class="navbar-nav">
                <a class="nav-link" href="index.php">Sinh Viên</a>
                <a class="nav-link" href="hocphan.php">Học Phần</a>
                <a class="nav-link" href="dangky.php">Đăng Ký</a>
                <a class="nav-link active" href="login.php">Đăng Nhập</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="login-container">
            <h2 class="text-center mb-4">ĐĂNG NHẬP</h2>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label for="masv" class="form-label">Mã SV</label>
                    <input type="text" class="form-control" id="masv" name="masv" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Đăng Nhập</button>
            </form>

            <a href="index.php" class="back-link">Back to List</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>