// main.js
document.addEventListener('DOMContentLoaded', function() {
  const postForm = document.getElementById('postForm');
  if (postForm) {
    postForm.addEventListener('submit', function(e) {
      e.preventDefault();
      const formData = new FormData(postForm);
      fetch('add_post.php', {
        method: 'POST',
        body: formData,
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      }).then(r => r.text()).then(txt => {
        if (txt.trim() === 'success') {
          document.getElementById('postMsg').innerHTML = '<div class="alert alert-success">Posted!</div>';
          // reload to show new post
          setTimeout(() => window.location.reload(), 800);
        } else {
          document.getElementById('postMsg').innerHTML = '<div class="alert alert-warning">' + txt + '</div>';
        }
      }).catch(err => {
        document.getElementById('postMsg').innerHTML = '<div class="alert alert-danger">Error.</div>';
      });
    });
  }
});
