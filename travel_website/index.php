<?php
require_once 'header.php';
?>
<div class="row">
  <div class="col-md-8">
    <h4>Latest Posts</h4>

    <?php
    // fetch posts with user info
    $sql = "SELECT p.*, u.name, u.profile_pic FROM posts p JOIN users u ON p.user_id = u.id ORDER BY p.created_at DESC";
    $res = $conn->query($sql);
    if ($res && $res->num_rows):
      while($post = $res->fetch_assoc()):
    ?>
      <div class="card card-post">
        <div class="card-body">
          <div class="d-flex align-items-center mb-2">
            <img src="<?= $post['profile_pic'] ? htmlspecialchars($post['profile_pic']) : 'default-avatar.png' ?>" class="profile-pic me-2" alt="pp">
            <div>
              <strong><?= htmlspecialchars($post['name']) ?></strong><br>
              <small class="text-muted"><?= date('d M Y H:i', strtotime($post['created_at'])) ?></small>
            </div>
          </div>
          <?php if($post['title']): ?><h5><?= htmlspecialchars($post['title']) ?></h5><?php endif; ?>
          <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
          <?php if($post['image']): ?>
            <img src="<?= htmlspecialchars($post['image']) ?>" class="img-fluid" alt="post image">
          <?php endif; ?>
          <?php if($post['place_name']): ?>
            <p class="mt-2"><strong>Place:</strong> <a href="place.php?name=<?= urlencode($post['place_name']) ?>"><?= htmlspecialchars($post['place_name']) ?></a></p>
          <?php endif; ?>
        </div>
      </div>
    <?php
      endwhile;
    else:
      echo "<p>No posts yet. Create one!</p>";
    endif;
    ?>
  </div>

  <div class="col-md-4">
    <?php if(is_logged_in()): ?>
      <div class="card mb-3">
        <div class="card-body">
          <h5>Create a Post</h5>
          <form id="postForm" method="post" enctype="multipart/form-data" action="add_post.php">
            <div class="mb-2"><input name="title" class="form-control" placeholder="Post title (optional)"></div>
            <div class="mb-2"><input name="place_name" class="form-control" placeholder="Place name (e.g., Manali)"></div>
            <div class="mb-2"><textarea name="content" class="form-control" placeholder="Share your travel..."></textarea></div>
            <div class="mb-2"><input type="file" name="image" accept="image/*" class="form-control"></div>
            <div id="postMsg"></div>
            <button class="btn btn-primary" type="submit">Post</button>
          </form>
        </div>
      </div>
    <?php else: ?>
      <div class="alert alert-info">Log in to create posts and view your profile.</div>
    <?php endif; ?>

    <div class="card">
      <div class="card-body">
        <h6>Search quick</h6>
        <form action="search.php" method="get">
          <div class="input-group">
            <input type="text" name="q" class="form-control" placeholder="Search place...">
            <button class="btn btn-outline-secondary" type="submit">Search</button>
          </div>
        </form>
      </div>
    </div>

  </div>
</div>

<?php require_once 'footer.php'; ?>
