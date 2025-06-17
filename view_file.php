<?php
session_start();
require 'db.php';

// ตั้งค่าเขตเวลาเป็นประเทศไทย
date_default_timezone_set('Asia/Bangkok');

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['file'])) {
    header("Location: index.php?error=missingfile");
    exit;
}

$user = $_SESSION['user'];
$filename = basename($_GET['file']);
$filepath = 'upload/' . $filename;

// ตรวจสอบว่าไฟล์มีอยู่จริง
if (!file_exists($filepath)) {
    header("Location: index.php?error=filenotfound");
    exit;
}

// บันทึกข้อมูลการดูไฟล์
$stmt = $conn->prepare("INSERT INTO file_views (filename, user_id, username, view_time) VALUES (?, ?, ?, NOW())");
$stmt->bind_param("sis", $filename, $user['id'], $user['username']);
$stmt->execute();

// Redirect ไปดูไฟล์
header("Location: " . $filepath);
exit;
?>