<?php
session_start();
require_once 'database.php';

if (!isset($_SESSION['masv'])) {
    header("Location: login.php");
    exit();
}

// Fetch student's registrations
$stmt = $conn->prepare("
    SELECT dk.MaDK, dk.NgayDK, hp.MaHP, hp.TenHP, hp.SoTinChi
    FROM DangKy dk
    JOIN ChiTietDangKy ctdk ON dk.MaDK = ctdk.MaDK
    JOIN HocPhan hp ON ctdk.MaHP = hp.MaHP
    WHERE dk.MaSV = :masv
    ORDER BY dk.NgayDK DESC
");
$stmt->bindParam(':masv', $_SESSION['masv']);
$stmt->execute();
$registrations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Đăng Ký</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">QUẢN LÝ SINH VIÊN</a>
            <div class="navbar-nav">
                <a class="nav-link" href="index.php">Sinh Viên</a>
                <a class="nav-link" href="hocphan.php">Học Phần</a>
                <a class="nav-link active" href="dangky.php">Đăng Ký</a>
                <a class="nav-link" href="logout.php">Đăng Xuất</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>DANH SÁCH ĐĂNG KÝ HỌC PHẦN</h2>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">Đăng ký học phần thành công!</div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>

        <div class="mb-3">
            <strong>Sinh viên:</strong> <?php echo htmlspecialchars($_SESSION['hoten']); ?>
            (<?php echo htmlspecialchars($_SESSION['masv']); ?>)
        </div>

        <?php if (empty($registrations)): ?>
            <div class="alert alert-info">Bạn chưa đăng ký học phần nào.</div>
        <?php else: ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Mã Đăng Ký</th>
                        <th>Ngày Đăng Ký</th>
                        <th>Mã HP</th>
                        <th>Tên Học Phần</th>
                        <th>Số Tín Chỉ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($registrations as $reg): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($reg['MaDK']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($reg['NgayDK'])); ?></td>
                            <td><?php echo htmlspecialchars($reg['MaHP']); ?></td>
                            <td><?php echo htmlspecialchars($reg['TenHP']); ?></td>
                            <td><?php echo htmlspecialchars($reg['SoTinChi']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <a href="hocphan.php" class="btn btn-primary">Đăng Ký Thêm Học Phần</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>