<?php
require_once 'header.php';
$q = isset($_GET['q']) ? trim($_GET['q']) : '';
?>
<div class="row">
  <div class="col-md-8">
    <h4>Search Results for "<?= htmlspecialchars($q) ?>"</h4>

    <?php if($q): 
      // find places
      $stmt = $conn->prepare("SELECT * FROM places WHERE name LIKE CONCAT('%', ?, '%') OR location LIKE CONCAT('%', ?, '%') LIMIT 20");
      $stmt->bind_param("ss", $q, $q);
      $stmt->execute();
      $places = $stmt->get_result();
      if ($places->num_rows):
        while($pl = $places->fetch_assoc()):
    ?>
      <div class="card mb-2">
        <div class="card-body">
          <h5><a href="place.php?id=<?= $pl['id'] ?>"><?= htmlspecialchars($pl['name']) ?></a></h5>
          <p><?= nl2br(htmlspecialchars($pl['info'])) ?></p>
          <p><small>Nearby: <?= htmlspecialchars($pl['nearby']) ?></small></p>
        </div>
      </div>
    <?php
        endwhile;
      else:
        echo "<p>No places found. You can still see user posts mentioning this place.</p>";
      endif;

      // show user posts mentioning that place name
      $stmt2 = $conn->prepare("SELECT p.*, u.name FROM posts p JOIN users u ON p.user_id=u.id WHERE p.place_name LIKE CONCAT('%', ?, '%') ORDER BY p.created_at DESC");
      $stmt2->bind_param("s", $q);
      $stmt2->execute();
      $pp = $stmt2->get_result();
      if ($pp->num_rows):
        echo "<h5 class='mt-3'>User posts mentioning '$q'</h5>";
        while($row=$pp->fetch_assoc()):
    ?>
      <div class="card mb-2">
        <div class="card-body"><strong><?= htmlspecialchars($row['name']) ?></strong>
          <p><?= nl2br(htmlspecialchars($row['content'])) ?></p>
        </div>
      </div>
    <?php
        endwhile;
      endif;

    else:
      echo "<p>Type a place to search.</p>";
    endif;
    ?>
  </div>

  <div class="col-md-4">
    <h6>Search form</h6>
    <form method="get">
      <div class="input-group mb-2">
        <input name="q" class="form-control" placeholder="Search (e.g., Manali)" value="<?= htmlspecialchars($q) ?>">
        <button class="btn btn-primary">Search</button>
      </div>
    </form>
  </div>
</div>

<?php require_once 'footer.php'; ?>
