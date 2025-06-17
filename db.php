<?php
// เชื่อมต่อฐานข้อมูล MySQL บน localhost โดยใช้ user root ไม่มีรหัสผ่าน (XAMPP default)
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'testdb';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
