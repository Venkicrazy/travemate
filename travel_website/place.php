<?php
require_once 'header.php';
$place = null;
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM places WHERE id = ? LIMIT 1");
    $stmt->bind_param("i",$id);
    $stmt->execute();
    $place = $stmt->get_result()->fetch_assoc();
} elseif (isset($_GET['name'])) {
    $name = trim($_GET['name']);
    $stmt = $conn->prepare("SELECT * FROM places WHERE name = ? LIMIT 1");
    $stmt->bind_param("s",$name);
    $stmt->execute();
    $place = $stmt->get_result()->fetch_assoc();
}

if (!$place) {
    echo "<p>Place not found.</p>";
    require_once 'footer.php';
    exit;
}
?>

<div class="row">
  <div class="col-md-8">
    <h3><?= htmlspecialchars($place['name']) ?></h3>
    <p><strong>Location:</strong> <?= htmlspecialchars($place['location']) ?></p>
    <p><?= nl2br(htmlspecialchars($place['info'])) ?></p>
    <p><strong>Nearby:</strong> <?= htmlspecialchars($place['nearby']) ?></p>

    <h5 class="mt-4">User posts about this place</h5>
    <?php
    $stmt = $conn->prepare("SELECT p.*, u.name FROM posts p JOIN users u ON p.user_id = u.id WHERE p.place_name LIKE CONCAT('%', ?, '%') ORDER BY p.created_at DESC");
    $stmt->bind_param("s", $place['name']);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows):
      while($r = $res->fetch_assoc()):
    ?>
      <div class="card mb-2">
        <div class="card-body">
          <strong><?= htmlspecialchars($r['name']) ?></strong>
          <p><?= nl2br(htmlspecialchars($r['content'])) ?></p>
        </div>
      </div>
    <?php endwhile; else: ?>
      <p>No posts yet.</p>
    <?php endif; ?>
  </div>
  <div class="col-md-4">
    <div class="card">
      <div class="card-body">
        <h6>Make a travel plan</h6>
        <p>Feature idea: generate plan based on duration, budget, and interests. (Later)</p>
      </div>
    </div>
  </div>
</div>

<?php require_once 'footer.php'; ?>
