<?php
require_once 'config.php';
if (!is_logged_in()) {
    header("Location: login.php");
    exit;
}
$user = current_user($conn);

// handle adding travel (simple)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_name'])) {
    $place = trim($_POST['place_name']);
    $vd = $_POST['visited_on'] ?: null;
    $notes = $_POST['notes'] ?: null;
    $stmt = $conn->prepare("INSERT INTO travels (user_id, place_name, visited_on, notes) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $user['id'], $place, $vd, $notes);
    $stmt->execute();
    header("Location: profile.php");
    exit;
}

require_once 'header.php';
?>
<div class="row">
  <div class="col-md-4">
    <div class="card">
      <div class="card-body text-center">
        <img src="<?= $user['profile_pic'] ?: 'default-avatar.png' ?>" class="profile-pic mb-2" alt="pp">
        <h5><?= htmlspecialchars($user['name']) ?></h5>
        <p><?= nl2br(htmlspecialchars($user['bio'])) ?></p>
      </div>
    </div>

    <div class="card mt-3">
      <div class="card-body">
        <h6>Add Travel Achievement</h6>
        <form method="post">
          <div class="mb-2"><input class="form-control" name="place_name" placeholder="Place name" required></div>
          <div class="mb-2"><input class="form-control" name="visited_on" type="date"></div>
          <div class="mb-2"><textarea class="form-control" name="notes" placeholder="Notes (optional)"></textarea></div>
          <button class="btn btn-success">Add</button>
        </form>
      </div>
    </div>

  </div>
  <div class="col-md-8">
    <h5>Your Travels</h5>
    <?php
    $stmt = $conn->prepare("SELECT * FROM travels WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->bind_param("i", $user['id']);
    $stmt->execute();
    $tr = $stmt->get_result();
    if ($tr->num_rows):
      while($t = $tr->fetch_assoc()):
    ?>
      <div class="card mb-2">
        <div class="card-body">
          <strong><?= htmlspecialchars($t['place_name']) ?></strong> <small class="text-muted"><?= $t['visited_on'] ?></small>
          <p><?= nl2br(htmlspecialchars($t['notes'])) ?></p>
        </div>
      </div>
    <?php endwhile; else: ?>
      <p>No travels yet.</p>
    <?php endif; ?>

    <h5 class="mt-4">Your Posts</h5>
    <?php
    $stmt = $conn->prepare("SELECT * FROM posts WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->bind_param("i", $user['id']);
    $stmt->execute();
    $ps = $stmt->get_result();
    if ($ps->num_rows):
      while($p = $ps->fetch_assoc()):
    ?>
      <div class="card mb-2">
        <div class="card-body">
          <p><?= nl2br(htmlspecialchars($p['content'])) ?></p>
          <?php if($p['image']): ?>
            <img src="<?= htmlspecialchars($p['image']) ?>" class="img-fluid">
          <?php endif; ?>
        </div>
      </div>
    <?php endwhile; else: ?>
      <p>No posts yet.</p>
    <?php endif; ?>

  </div>
</div>

<?php require_once 'footer.php'; ?>
