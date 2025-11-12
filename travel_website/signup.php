<?php
require_once 'config.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if (!$name || !$email || !$password) $errors[] = "All fields required.";

    if (!$errors) {
        // check duplicate
        $stmt = $conn->prepare("SELECT id FROM users WHERE email=? LIMIT 1");
        $stmt->bind_param("s",$email);
        $stmt->execute();
        if ($stmt->get_result()->num_rows) {
            $errors[] = "Email already exists.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $hash);
            if ($stmt->execute()) {
                $_SESSION['user_id'] = $stmt->insert_id;
                header("Location: index.php");
                exit;
            } else {
                $errors[] = "DB error: " . $conn->error;
            }
        }
    }
}
require_once 'header.php';
?>
<div class="row justify-content-center">
  <div class="col-md-6">
    <h3>Sign up</h3>
    <?php if($errors): ?>
      <div class="alert alert-danger"><?= implode('<br>',$errors) ?></div>
    <?php endif; ?>
    <form method="post">
      <div class="mb-2"><input class="form-control" name="name" placeholder="Full name" required></div>
      <div class="mb-2"><input class="form-control" name="email" type="email" placeholder="Email" required></div>
      <div class="mb-2"><input class="form-control" name="password" type="password" placeholder="Password" required></div>
      <button class="btn btn-primary">Sign up</button>
    </form>
  </div>
</div>
<?php require_once 'footer.php'; ?>
