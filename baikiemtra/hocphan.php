<?php
session_start();
require_once 'database.php';

// Fetch all courses
$stmt = $conn->prepare("SELECT * FROM HocPhan");
$stmt->execute();
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Học Phần</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">SINH VIÊN</a>
            <div class="navbar-nav">
                <a class="nav-link" href="index.php">Sinh Viên</a>
                <a class="nav-link active" href="hocphan.php">Học Phần</a>
                <a class="nav-link" href="dangky.php">Đăng Ký</a>
                <a class="nav-link" href="login.php">Đăng Nhập</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>DANH SÁCH HỌC PHẦN</h2>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Mã Học Phần</th>
                    <th>Tên Học Phần</th>
                    <th>Số Tín Chỉ</th>
                    <th>Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($courses as $course): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($course['MaHP']); ?></td>
                        <td><?php echo htmlspecialchars($course['TenHP']); ?></td>
                        <td><?php echo htmlspecialchars($course['SoTinChi']); ?></td>
                        <td>
                            <?php if (isset($_SESSION['masv'])): ?>
                                <form action="dangky_process.php" method="POST" style="display: inline;">
                                    <input type="hidden" name="mahp" value="<?php echo $course['MaHP']; ?>">
                                    <button type="submit" class="btn btn-sm btn-success">Đăng ký</button>
                                </form>
                            <?php else: ?>
                                <a href="login.php" class="btn btn-sm btn-primary">Đăng nhập để đăng ký</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>