<?php
session_start();

// ต้อง login และเป็น admin เท่านั้น
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// ตรวจสอบว่าได้รับชื่อไฟล์มาหรือไม่
if (!isset($_GET['file'])) {
    header("Location: index.php?error=missingfile");
    exit;
}

$filename = basename($_GET['file']);
$filepath = 'upload/' . $filename;

// ลบไฟล์หากมีอยู่
if (file_exists($filepath)) {
    if (unlink($filepath)) {
        header("Location: index.php?delete=success");
        exit;
    } else {
        header("Location: index.php?delete=fail");
        exit;
    }
} else {
    header("Location: index.php?delete=notfound");
    exit;
}
?>
