<?php
require_once 'database.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$masv = $_GET['id'];

try {
    // Get student image before deleting
    $stmt = $conn->prepare("SELECT Hinh FROM SinhVien WHERE MaSV = :masv");
    $stmt->bindParam(':masv', $masv);
    $stmt->execute();
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    // Delete related records in ChiTietDangKy and DangKy first
    $stmt = $conn->prepare("
        DELETE ctdk FROM ChiTietDangKy ctdk
        INNER JOIN DangKy dk ON ctdk.MaDK = dk.MaDK
        WHERE dk.MaSV = :masv
    ");
    $stmt->bindParam(':masv', $masv);
    $stmt->execute();

    // Delete from DangKy
    $stmt = $conn->prepare("DELETE FROM DangKy WHERE MaSV = :masv");
    $stmt->bindParam(':masv', $masv);
    $stmt->execute();

    // Delete the student
    $stmt = $conn->prepare("DELETE FROM SinhVien WHERE MaSV = :masv");
    $stmt->bindParam(':masv', $masv);
    $stmt->execute();

    // Delete the image file if it exists
    if ($student && $student['Hinh'] && file_exists($student['Hinh'])) {
        unlink($student['Hinh']);
    }

    header("Location: index.php");
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
