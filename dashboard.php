<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8" />
  <title>Dashboard</title>
</head>
<body>
<h2>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h2>

<?php if ($user['role'] === 'admin'): ?>
    <a href="register.php">Go to Register (Add User)</a><br/>
<?php endif; ?>

<a href="index.php">Go to Uploaded Files</a><br/>
<a href="logout.php">Logout</a>
</body>
</html>
