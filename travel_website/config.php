<?php
session_start();

$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = ''; // XAMPP default blank; change if you set a password
$DB_NAME = 'travelmate';

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($conn->connect_error) {
    die("DB Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function current_user($conn) {
    if (!is_logged_in()) return null;
    $id = intval($_SESSION['user_id']);
    $stmt = $conn->prepare("SELECT id, name, email, bio, profile_pic FROM users WHERE id = ?");
    $stmt->bind_param("i",$id);
    $stmt->execute();
    $res = $stmt->get_result();
    return $res->fetch_assoc();
}
?>
