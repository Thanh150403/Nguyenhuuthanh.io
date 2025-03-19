<?php
session_start();
require_once 'database.php';

if (!isset($_SESSION['masv'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['mahp'])) {
    try {
        $masv = $_SESSION['masv'];
        $mahp = $_POST['mahp'];

        // Create new registration
        $stmt = $conn->prepare("INSERT INTO DangKy (NgayDK, MaSV) VALUES (NOW(), :masv)");
        $stmt->bindParam(':masv', $masv);
        $stmt->execute();

        $madk = $conn->lastInsertId();

        // Add course to registration details
        $stmt = $conn->prepare("INSERT INTO ChiTietDangKy (MaDK, MaHP) VALUES (:madk, :mahp)");
        $stmt->bindParam(':madk', $madk);
        $stmt->bindParam(':mahp', $mahp);
        $stmt->execute();

        header("Location: dangky.php?success=1");
    } catch (PDOException $e) {
        header("Location: dangky.php?error=" . urlencode($e->getMessage()));
    }
} else {
    header("Location: hocphan.php");
}
