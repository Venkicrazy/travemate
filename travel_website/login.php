<?php
require_once 'config.php';
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if (!$email || !$password) $errors[] = "All fields required.";

    if (!$errors) {
        $stmt = $conn->prepare("SELECT id, password FROM users WHERE email=? LIMIT 1");
        $stmt->bind_param("s",$email);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows) {
            $u = $res->fetch_assoc();
            if (password_verify($password, $u['password'])) {
                $_SESSION['user_id'] = $u['id'];
                header("Location: index.php");
                exit;
            } else {
                $errors[] = "Invalid credentials.";
            }
        } else {
            $errors[] = "Invalid credentials.";
        }
    }
}
require_once 'header.php';
?>
<div class="row justify-content-center">
  <div class="col-md-6">
    <h3>Log in</h3>
    <?php if($errors): ?>
      <div class="alert alert-danger"><?= implode('<br>',$errors) ?></div>
    <?php endif; ?>
    <form method="post">
      <div class="mb-2"><input class="form-control" name="email" type="email" placeholder="Email" required></div>
      <div class="mb-2"><input class="form-control" name="password" type="password" placeholder="Password" required></div>
      <button class="btn btn-primary">Log in</button>
    </form>
  </div>
</div>
<?php require_once 'footer.php'; ?>
