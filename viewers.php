<?php
session_start();
require 'db.php';

date_default_timezone_set('Asia/Bangkok');

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
$user = $_SESSION['user'];
if ($user['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}
if (!isset($_GET['file'])) {
    header("Location: index.php?error=missingfile");
    exit;
}
$filename = basename($_GET['file']);
$stmt = $conn->prepare("SELECT username, view_time FROM file_views WHERE filename = ? ORDER BY view_time DESC");
$stmt->bind_param("s", $filename);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ดูผู้เข้าชมไฟล์ <?php echo htmlspecialchars($filename); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .main-container {
            margin-top: 100px;
            margin-bottom: 2rem;
        }
        .content-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            margin-bottom: 2rem;
        }
        .page-title {
            color: #2196F3;
            font-weight: 700;
            margin-bottom: 1.5rem;
            font-size: 2rem;
        }
        .btn-custom {
            border-radius: 25px;
            padding: 0.7rem 1.5rem;
            font-weight: 600;
            border: none;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        .btn-primary-custom {
            background: linear-gradient(135deg, #2196F3, #1976D2);
            color: white;
        }
        .table-custom {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }
        .table-custom thead {
            background: linear-gradient(135deg, #2196F3, #1976D2);
        }
        .table-custom thead th {
            border: none;
            color: #222 !important;
            font-weight: 600;
            padding: 1rem;
            background: #fff !important;
        }
        .table-custom tbody td {
            border: none;
            padding: 1rem;
            vertical-align: middle;
        }
        .table-custom tbody tr:nth-child(even) {
            background: #f8f9fa;
        }
        .table-custom tbody tr:hover {
            background: #e3f2fd;
            transition: all 0.3s ease;
        }
        @media (max-width: 768px) {
            .main-container {
                margin-top: 1rem;
                padding: 0 1rem;
            }
            .content-card {
                padding: 1.5rem;
            }
            .page-title {
                font-size: 1.5rem;
            }
            .btn-custom {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="container main-container">
        <div class="content-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="page-title mb-0">
                    <i class="fas fa-users me-2"></i>
                    รายชื่อผู้เข้าชมไฟล์: <span class="text-primary"><?php echo htmlspecialchars($filename); ?></span>
                </h1>
                <a href="index.php" class="btn btn-primary-custom btn-custom">
                    <i class="fas fa-arrow-left"></i>
                    กลับหน้าหลัก
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th scope="col"><i class="fas fa-hashtag me-1"></i> ลำดับ</th>
                            <th scope="col"><i class="fas fa-user me-1"></i> ชื่อผู้ใช้งาน</th>
                            <th scope="col"><i class="fas fa-clock me-1"></i> เวลาเปิดไฟล์</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo '<tr>
                                        <td>' . $i . '</td>
                                        <td>' . htmlspecialchars($row['username']) . '</td>
                                        <td>' . date("d/m/Y H:i:s", strtotime($row['view_time'])) . '</td>
                                      </tr>';
                                $i++;
                            }
                        } else {
                            echo '<tr>
                                    <td colspan="3" class="text-center text-muted py-4">
                                        <i class="fas fa-user-slash fa-2x mb-2 d-block"></i>
                                        ยังไม่มีการเข้าชมไฟล์นี้
                                    </td>
                                  </tr>';
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