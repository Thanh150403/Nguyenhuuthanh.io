<?php
require_once 'database.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$masv = $_GET['id'];

// Fetch student data
$stmt = $conn->prepare("SELECT * FROM SinhVien WHERE MaSV = :masv");
$stmt->bindParam(':masv', $masv);
$stmt->execute();
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    header("Location: index.php");
    exit();
}

// Fetch all majors for the dropdown
$stmt = $conn->prepare("SELECT * FROM NganhHoc");
$stmt->execute();
$majors = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $hoten = $_POST['hoten'];
        $gioitinh = $_POST['gioitinh'];
        $ngaysinh = $_POST['ngaysinh'];
        $manganh = $_POST['manganh'];

        // Handle image upload
        $hinh = $student['Hinh']; // Keep existing image by default
        if (isset($_FILES['hinh']) && $_FILES['hinh']['error'] == 0) {
            $target_dir = "Content/images/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $hinh = $target_dir . basename($_FILES["hinh"]["name"]);
            if (move_uploaded_file($_FILES["hinh"]["tmp_name"], $hinh)) {
                // Delete old image if exists and different
                if ($student['Hinh'] && $student['Hinh'] != $hinh && file_exists($student['Hinh'])) {
                    unlink($student['Hinh']);
                }
            } else {
                throw new Exception("Sorry, there was an error uploading your file.");
            }
        }

        $sql = "UPDATE SinhVien SET 
                HoTen = :hoten,
                GioiTinh = :gioitinh,
                NgaySinh = :ngaysinh,
                Hinh = :hinh,
                MaNganh = :manganh
                WHERE MaSV = :masv";

        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':hoten', $hoten);
        $stmt->bindParam(':gioitinh', $gioitinh);
        $stmt->bindParam(':ngaysinh', $ngaysinh);
        $stmt->bindParam(':hinh', $hinh);
        $stmt->bindParam(':manganh', $manganh);
        $stmt->bindParam(':masv', $masv);

        $stmt->execute();

        header("Location: index.php");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh Sửa Sinh Viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">QUẢN LÝ SINH VIÊN</a>
            <div class="navbar-nav">
                <a class="nav-link" href="index.php">Trang Chủ</a>
                <a class="nav-link" href="create.php">Thêm Sinh Viên</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Chỉnh Sửa Sinh Viên</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="masv" class="form-label">MSSV</label>
                <input type="text" class="form-control" id="masv" value="<?php echo htmlspecialchars($student['MaSV']); ?>" readonly>
            </div>

            <div class="mb-3">
                <label for="hoten" class="form-label">Họ Tên</label>
                <input type="text" class="form-control" id="hoten" name="hoten"
                    value="<?php echo htmlspecialchars($student['HoTen']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="gioitinh" class="form-label">Giới Tính</label>
                <select class="form-control" id="gioitinh" name="gioitinh" required>
                    <option value="Nam" <?php echo $student['GioiTinh'] == 'Nam' ? 'selected' : ''; ?>>Nam</option>
                    <option value="Nữ" <?php echo $student['GioiTinh'] == 'Nữ' ? 'selected' : ''; ?>>Nữ</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="ngaysinh" class="form-label">Ngày Sinh</label>
                <input type="date" class="form-control" id="ngaysinh" name="ngaysinh"
                    value="<?php echo date('Y-m-d', strtotime($student['NgaySinh'])); ?>" required>
            </div>

            <div class="mb-3">
                <label for="hinh" class="form-label">Hình</label>
                <?php if ($student['Hinh']): ?>
                    <div class="mb-2">
                        <img src="<?php echo htmlspecialchars($student['Hinh']); ?>"
                            alt="Current Image" style="max-width: 200px;">
                    </div>
                <?php endif; ?>
                <input type="file" class="form-control" id="hinh" name="hinh" accept="image/*">
            </div>

            <div class="mb-3">
                <label for="manganh" class="form-label">Ngành</label>
                <select class="form-control" id="manganh" name="manganh" required>
                    <?php foreach ($majors as $major): ?>
                        <option value="<?php echo htmlspecialchars($major['MaNganh']); ?>"
                            <?php echo $student['MaNganh'] == $major['MaNganh'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($major['TenNganh']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Cập Nhật</button>
            <a href="index.php" class="btn btn-secondary">Hủy</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>