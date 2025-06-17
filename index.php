<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// ตั้งค่าเขตเวลาเป็นประเทศไทย
date_default_timezone_set('Asia/Bangkok');

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - File Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar-custom { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border: none; box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);}
        .navbar-brand { font-weight: 700; color: #2196F3 !important; font-size: 1.5rem;}
        .main-container { margin-top: 2rem; margin-bottom: 2rem;}
        .welcome-card { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border-radius: 20px; padding: 2rem; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1); border: 1px solid rgba(255, 255, 255, 0.2); margin-bottom: 2rem;}
        .welcome-title { color: #2196F3; font-weight: 700; margin-bottom: 0.5rem;}
        .user-info { color: #666; font-size: 1.1rem;}
        .user-role { display: inline-block; background: linear-gradient(135deg, #2196F3, #1976D2); color: white; padding: 0.3rem 1rem; border-radius: 20px; font-size: 0.9rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;}
        .content-card { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border-radius: 20px; padding: 2rem; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1); border: 1px solid rgba(255, 255, 255, 0.2);}
        .page-title { color: #2196F3; font-weight: 700; margin-bottom: 1.5rem; font-size: 2rem;}
        .btn-custom { border-radius: 25px; padding: 0.7rem 1.5rem; font-weight: 600; border: none; transition: all 0.3s ease; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;}
        .btn-custom:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);}
        .btn-primary-custom { background: linear-gradient(135deg, #2196F3, #1976D2); color: white;}
        .btn-success-custom { background: linear-gradient(135deg, #4CAF50, #388E3C); color: white;}
        .btn-danger-custom { background: linear-gradient(135deg, #f44336, #d32f2f); color: white;}
        .btn-info-custom { background: linear-gradient(135deg, #00BCD4, #0097A7); color: white;}
        .btn-outline-danger-custom { border: 2px solid #f44336; color: #f44336; background: transparent;}
        .btn-outline-danger-custom:hover { background: #f44336; color: white;}
        .btn-outline-success-custom { border: 2px solid #4CAF50; color: #4CAF50; background: transparent;}
        .btn-outline-success-custom:hover { background: #4CAF50; color: white;}
        .table-custom { background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);}
        .table-custom thead { background: linear-gradient(135deg, #2196F3, #1976D2);}
        .table-custom thead th {
            border: none;
            color: #222 !important;
            font-weight: 600;
            padding: 1rem;
            background: #fff !important;
        }
        .table-custom tbody td { border: none; padding: 1rem; vertical-align: middle;}
        .table-custom tbody tr:nth-child(even) { background: #f8f9fa;}
        .table-custom tbody tr:hover { background: #e3f2fd; transition: all 0.3s ease;}
        .alert-custom { border: none; border-radius: 15px; padding: 1rem 1.5rem; margin-bottom: 1.5rem;}
        .alert-success-custom { background: linear-gradient(135deg, #e8f5e8, #c8e6c9); color: #2e7d32; border-left: 4px solid #4caf50;}
        .stats-row { margin-bottom: 2rem;}
        .stat-card { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border-radius: 15px; padding: 1.5rem; text-align: center; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1); border: 1px solid rgba(255, 255, 255, 0.2); transition: all 0.3s ease;}
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);}
        .stat-number { font-size: 2.5rem; font-weight: 700; color: #2196F3; margin-bottom: 0.5rem;}
        .stat-label { color: #666; font-weight: 600;}
        .action-buttons { display: flex; gap: 0.5rem; flex-wrap: wrap;}
        @media (max-width: 768px) {
            .main-container { margin-top: 1rem; padding: 0 1rem;}
            .welcome-card, .content-card { padding: 1.5rem;}
            .page-title { font-size: 1.5rem;}
            .action-buttons { flex-direction: column;}
            .btn-custom { width: 100%; justify-content: center;}
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-cloud-upload-alt me-2"></i>
                File Management System
            </a>
            <div class="navbar-nav ms-auto d-flex flex-row gap-2">
                <?php if ($user['role'] === 'admin'): ?>
                    <a href="register.php" class="btn btn-outline-success-custom btn-custom">
                        <i class="fas fa-user-plus"></i>
                        Register
                    </a>
                <?php endif; ?>
                <a href="logout.php" class="btn btn-outline-danger-custom btn-custom">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container main-container" style="margin-top: 100px;">
        <!-- Welcome Section -->
        <div class="welcome-card">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="welcome-title">
                        <i class="fas fa-user-circle me-2"></i>
                        ยินดีต้อนรับ, <?php echo htmlspecialchars($user['username']); ?>!
                    </h2>
                    <p class="user-info">
                        สถานะ: <span class="user-role"><?php echo htmlspecialchars($user['role']); ?></span>
                    </p>
                </div>
                <div class="col-md-4 text-end">
                    <div class="stat-card">
                        <div class="stat-number">
                            <?php
                            $uploadDir = 'upload/';
                            $fileCount = 0;
                            if (is_dir($uploadDir)) {
                                $files = array_diff(scandir($uploadDir), array('.', '..'));
                                $fileCount = count(array_filter($files, function($file) use ($uploadDir) {
                                    return is_file($uploadDir . $file);
                                }));
                            }
                            echo $fileCount;
                            ?>
                        </div>
                        <div class="stat-label">ไฟล์ทั้งหมด</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="content-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="page-title mb-0">
                    <i class="fas fa-folder-open me-2"></i>
                    รายการไฟล์ที่อัปโหลด
                </h1>
                <?php if ($user['role'] === 'admin'): ?>
                    <a href="formforupload.html" class="btn btn-primary-custom btn-custom">
                        <i class="fas fa-plus"></i>
                        Upload File
                    </a>
                <?php endif; ?>
            </div>

            <?php if (isset($_GET['upload']) && $_GET['upload'] === 'success'): ?>
                <div class="alert alert-success-custom alert-custom">
                    <i class="fas fa-check-circle me-2"></i>
                    อัปโหลดไฟล์สำเร็จ!
                </div>
            <?php endif; ?>

            <!-- File Table -->
            <div class="table-responsive">
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag me-1"></i>ลำดับ</th>
                            <th><i class="fas fa-file me-1"></i>ชื่อไฟล์</th>
                            <th><i class="fas fa-tag me-1"></i>สกุลไฟล์</th>
                            <th><i class="fas fa-calendar me-1"></i>วันอัปโหลด</th>
                            <th><i class="fas fa-tools me-1"></i>จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $uploadDir = 'upload/';
                        if (is_dir($uploadDir)) {
                            $files = array_diff(scandir($uploadDir), array('.', '..'));
                            $fileData = array();

                            // เก็บข้อมูลไฟล์และวันที่สร้างไว้ในอาร์เรย์
                            foreach ($files as $file) {
                                $filePath = $uploadDir . $file;
                                if (is_file($filePath)) {
                                    $fileData[] = array(
                                        'name' => $file,
                                        'path' => $filePath,
                                        'mtime' => filemtime($filePath)
                                    );
                                }
                            }

                            // เรียงไฟล์ตามวันที่แก้ไขล่าสุด (ไฟล์เก่าสุดไปใหม่สุด)
                            usort($fileData, function($a, $b) {
                                return $a['mtime'] - $b['mtime'];
                            });

                            $index = 1;
                            foreach ($fileData as $fileInfo) {
                                $file = $fileInfo['name'];
                                $filePath = $fileInfo['path'];
                                
                                $pathInfo = pathinfo($file);
                                $name = $pathInfo['filename'];
                                $ext = $pathInfo['extension'];
                                $uploadDate = date("d/m/Y H:i:s", $fileInfo['mtime']);
                                $viewLink = "view_file.php?file=" . urlencode($file);
                                $deleteLink = "delete.php?file=" . urlencode($file);
                                $viewersLink = "viewers.php?file=" . urlencode($file);

                                echo "<tr>
                                        <td><strong>{$index}</strong></td>
                                        <td>" . htmlspecialchars($name) . "</td>
                                        <td><span class='badge bg-primary'>" . strtoupper(htmlspecialchars($ext)) . "</span></td>
                                        <td>{$uploadDate}</td>
                                        <td>
                                            <div class='action-buttons'>
                                                <a href='" . htmlspecialchars($viewLink) . "' target='_blank' class='btn btn-info-custom btn-custom btn-sm'>
                                                    <i class='fas fa-eye'></i>
                                                    ดู
                                                </a>";
                                
                                if ($user['role'] === 'admin') {
                                    echo "<a href='" . htmlspecialchars($viewersLink) . "' class='btn btn-success-custom btn-custom btn-sm'>
                                            <i class='fas fa-users'></i>
                                            ดูผู้เข้าชม
                                          </a>
                                          <a href='" . htmlspecialchars($deleteLink) . "' class='btn btn-danger-custom btn-custom btn-sm' onclick=\"return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบไฟล์นี้?');\">
                                            <i class='fas fa-trash'></i>
                                            ลบ
                                          </a>";
                                }
                                
                                echo "      </div>
                                        </td>
                                      </tr>";
                                $index++;
                            }

                            if (empty($fileData)) {
                                echo '<tr><td colspan="5" class="text-center text-muted py-4">
                                        <i class="fas fa-folder-open fa-3x mb-3 d-block"></i>
                                        ไม่มีไฟล์ในระบบ
                                      </td></tr>';
                            }
                        } else {
                            echo '<tr><td colspan="5" class="text-center text-danger py-4">
                                    <i class="fas fa-exclamation-triangle fa-2x mb-2 d-block"></i>
                                    ไม่พบโฟลเดอร์ upload
                                  </td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>