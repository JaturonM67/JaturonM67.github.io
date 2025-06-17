<?php
// ตั้งค่าเขตเวลาเป็นประเทศไทย
date_default_timezone_set('Asia/Bangkok');

$target_dir = "upload/";
if (!is_dir($target_dir)) {
    mkdir($target_dir, 0777, true); // สร้างโฟลเดอร์ถ้าไม่มี
}

if (!isset($_FILES['file'])) {
    header("Location: formforupload.html");
    exit;
}

$filename = basename($_FILES["file"]["name"]);
$target_file = $target_dir . $filename;

// ตรวจสอบนามสกุลไฟล์
$allowed_extensions = ['doc', 'docx', 'xls', 'xlsx', 'pdf', 'ppt', 'pptx', 'txt'];
$file_extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

if (!in_array($file_extension, $allowed_extensions)) {
    $error = "❌ ไฟล์ชนิดนี้ไม่ถูกต้อง! รองรับเฉพาะไฟล์: " . implode(", ", $allowed_extensions);
} else {
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        // ตั้งค่าเวลาปัจจุบันของไทยให้กับไฟล์ที่อัปโหลด
        touch($target_file, time());
        header("Location: index.php?upload=success");
        exit();
    } else {
        $error = "❌ มีข้อผิดพลาดในการอัปโหลดไฟล์ กรุณาลองใหม่อีกครั้ง"; 
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8" />
  <title>ผลการอัปโหลดไฟล์</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
  <div class="container mt-5">
    <div class="alert alert-danger text-center" role="alert">
      <h4 class="alert-heading"><?= isset($error) ? htmlspecialchars($error) : '❌ มีข้อผิดพลาดในการอัปโหลดไฟล์' ?></h4>
      <p>กรุณาลองอีกครั้งหรือกลับไปหน้าอัปโหลด</p>
      <div class="d-flex justify-content-center gap-3 mt-3">
        <a href="formforupload.html" class="btn btn-secondary btn-lg">กลับไปหน้าอัปโหลด</a>
        <a href="index.php" class="btn btn-dark btn-lg">กลับหน้าหลัก</a>
      </div>
    </div>
  </div>
</body>
</html>