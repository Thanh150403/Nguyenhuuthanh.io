<?php
require_once 'database.php';

// Fetch all majors for the dropdown
$stmt = $conn->prepare("SELECT * FROM NganhHoc");
$stmt->execute();
$majors = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $masv = $_POST['masv'];
        $hoten = $_POST['hoten'];
        $gioitinh = $_POST['gioitinh'];
        $ngaysinh = $_POST['ngaysinh'];
        $manganh = $_POST['manganh'];

        // Handle image upload
        $hinh = '';
        if (isset($_FILES['hinh']) && $_FILES['hinh']['error'] == 0) {
            $target_dir = "Content/images/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $hinh = $target_dir . basename($_FILES["hinh"]["name"]);
            if (move_uploaded_file($_FILES["hinh"]["tmp_name"], $hinh)) {
                // File uploaded successfully
            } else {
                throw new Exception("Sorry, there was an error uploading your file.");
            }
        }

        $sql = "INSERT INTO SinhVien (MaSV, HoTen, GioiTinh, NgaySinh, Hinh, MaNganh) 
                VALUES (:masv, :hoten, :gioitinh, :ngaysinh, :hinh, :manganh)";
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':masv', $masv);
        $stmt->bindParam(':hoten', $hoten);
        $stmt->bindParam(':gioitinh', $gioitinh);
        $stmt->bindParam(':ngaysinh', $ngaysinh);
        $stmt->bindParam(':hinh', $hinh);
        $stmt->bindParam(':manganh', $manganh);

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
    <title>Thêm Sinh Viên Mới</title>
    <link rel="stylesheet" href="style.css">

</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">QUẢN LÝ SINH VIÊN</a>
            <div class="navbar-nav">
                <a class="nav-link" href="index.php">Trang Chủ</a>
                <a class="nav-link active" href="create.php">Thêm Sinh Viên</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Thêm Sinh Viên Mới</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="masv" class="form-label">MSSV</label>
                <input type="text" class="form-control" id="masv" name="masv" required>
            </div>

            <div class="mb-3">
                <label for="hoten" class="form-label">Họ Tên</label>
                <input type="text" class="form-control" id="hoten" name="hoten" required>
            </div>

            <div class="mb-3">
                <label for="gioitinh" class="form-label">Giới Tính</label>
                <select class="form-control" id="gioitinh" name="gioitinh" required>
                    <option value="Nam">Nam</option>
                    <option value="Nữ">Nữ</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="ngaysinh" class="form-label">Ngày Sinh</label>
                <input type="date" class="form-control" id="ngaysinh" name="ngaysinh" required>
            </div>

            <div class="mb-3">
                <label for="hinh" class="form-label">Hình</label>
                <input type="file" class="form-control" id="hinh" name="hinh" accept="image/*">
            </div>

            <div class="mb-3">
                <label for="manganh" class="form-label">Ngành</label>
                <select class="form-control" id="manganh" name="manganh" required>
                    <?php foreach ($majors as $major): ?>
                        <option value="<?php echo htmlspecialchars($major['MaNganh']); ?>">
                            <?php echo htmlspecialchars($major['TenNganh']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Thêm Sinh Viên</button>
            <a href="index.php" class="btn btn-secondary">Hủy</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>