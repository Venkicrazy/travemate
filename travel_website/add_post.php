<?php
require_once 'config.php';
if (!is_logged_in()) {
    http_response_code(403);
    echo "Please log in.";
    exit;
}

$user_id = intval($_SESSION['user_id']);
$title = isset($_POST['title']) ? trim($_POST['title']) : null;
$content = isset($_POST['content']) ? trim($_POST['content']) : null;
$place_name = isset($_POST['place_name']) ? trim($_POST['place_name']) : null;

$image_path = null;
if (!empty($_FILES['image']) && $_FILES['image']['error'] == 0) {
    $allowed = ['image/png','image/jpeg','image/jpg','image/gif'];
    $type = mime_content_type($_FILES['image']['tmp_name']);
    if (in_array($type, $allowed)) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $fname = 'uploads/' . uniqid('img_') . "." . $ext;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $fname)) {
            $image_path = $fname;
        }
    }
}

$stmt = $conn->prepare("INSERT INTO posts (user_id, title, content, image, place_name) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("issss", $user_id, $title, $content, $image_path, $place_name);
if ($stmt->execute()) {
    // redirect back to index (or echo success for AJAX)
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
        echo "success";
    } else {
        header("Location: index.php");
    }
} else {
    echo "Error: " . $conn->error;
}
