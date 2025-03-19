<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'database.php';

// Fetch all students with their major information
$stmt = $conn->prepare("
    SELECT sv.*, nh.TenNganh 
    FROM SinhVien sv 
    LEFT JOIN NganhHoc nh ON sv.MaNganh = nh.MaNganh
");
$stmt->execute();
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Sinh Viên</title>
    <link rel="stylesheet" href="style.css"> <!-- Liên kết với file CSS của bạn -->
</head>

<body>
    <div class="navbar">
        <div class="container">
            <a class="navbar-brand" href="#">QUẢN LÝ SINH VIÊN</a>
            <div class="navbar-nav">
                <a href="index.php">Sinh Viên</a>
                <a href="hocphan.php">Học Phần</a>
                <?php if (isset($_SESSION['masv'])): ?>
                    <a href="dangky.php">Đăng Ký</a>
                    <a href="logout.php">Đăng Xuất</a>
                <?php else: ?>
                    <a href="login.php">Đăng Nhập</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="container">
        <h2>TRANG SINH VIÊN</h2>
        <a href="create.php" class="btn">Thêm Sinh Viên</a>

        <table class="table">
            <thead>
                <tr>
                    <th>MSSV</th>
                    <th>Họ Tên</th>
                    <th>Giới Tính</th>
                    <th>Ngày Sinh</th>
                    <th>Hình</th>
                    <th>Ngành</th>
                    <th>Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($student['MaSV']); ?></td>
                        <td><?php echo htmlspecialchars($student['HoTen']); ?></td>
                        <td><?php echo htmlspecialchars($student['GioiTinh']); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($student['NgaySinh'])); ?></td>
                        <td>
                            <?php if ($student['Hinh']): ?>
                                <img src="<?php echo htmlspecialchars($student['Hinh']); ?>"
                                     alt="Student Image" class="student-image">
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($student['TenNganh']); ?></td>
                        <td>
                            <a href="edit.php?id=<?php echo $student['MaSV']; ?>" class="btn-edit">Sửa</a>
                            <a href="detail.php?id=<?php echo $student['MaSV']; ?>" class="btn-info">Chi tiết</a>
                            <a href="delete.php?id=<?php echo $student['MaSV']; ?>" class="btn-delete"
                               onclick="return confirm('Bạn có chắc muốn xóa sinh viên này?')">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>
