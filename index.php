<?php
session_start();
require_once __DIR__ . '/config.php';

// Helper: produce safe filename and URL path for a sermon file
function safe_basename($path){
    return htmlspecialchars(basename($path), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

$sermons_dir = __DIR__ . '/sermons';
$sermons_url_path = 'sermons/';

// Ensure sermons dir exists
if (!is_dir($sermons_dir)) {
    mkdir($sermons_dir, 0777, true);
}

// Scan sermons directory and build list (newest first)
$files = [];
$dh = opendir($sermons_dir);
if ($dh) {
    while (($file = readdir($dh)) !== false) {
        if ($file === '.' || $file === '..') continue;
        $full = $sermons_dir . '/' . $file;
        if (is_file($full)) {
            $files[filemtime($full) . '|' . $file] = $file;
        }
    }
    closedir($dh);
}
krsort($files, SORT_NUMERIC);

$msg = isset($_GET['msg']) ? htmlspecialchars($_GET['msg']) : '';
$logged_in = !empty($_SESSION['logged_in']);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Life Way — Sermons</title>
  <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
  <header class="site-header">
    <div class="container">
      <h1 class="logo">Life Way — Sermons</h1>
      <nav class="nav">
        <a href="#playlist">Playlist</a>
        <?php if ($logged_in): ?>
          <a href="admin/logout.php">Logout</a>
        <?php else: ?>
          <a href="admin/login.php">Admin</a>
        <?php endif; ?>
      </nav>
    </div>
  </header>

  <main class="container">
    <section class="hero">
      <div class="hero-left">
        <h2>Listen. Download. Be encouraged.</h2>
        <p>Find recent sermons, play them in the browser or download to listen later.</p>
        <?php if ($msg): ?>
          <div class="note" style="margin-top:12px;background:#05250a;padding:8px;border-radius:6px;color:#bdebb0;">
            <?php echo $msg; ?>
          </div>
        <?php endif; ?>
      </div>
      <div class="player-card">
        <div id="current-info">
          <div class="cover" id="cover">LW</div>
          <div>
            <div id="title">Select a sermon</div>
            <div id="artist">Life Way Charismatic Church</div>
          </div>
        </div>
        <audio id="audio" controls preload="none"></audio>
      </div>
    </section>

    <section id="playlist" class="playlist">
      <h3>Playlist</h3>
      <ul id="tracks">
        <?php
        if (count($files) === 0) {
            echo '<li class="empty">No sermons yet.</li>';
        } else {
            foreach ($files as $file) {
                $fileEsc = rawurlencode($file);
                $fileUrl = $sermons_url_path . $fileEsc;
                $title = safe_basename($file);
                echo '<li data-src="' . $fileUrl . '" data-title="' . $title . '">' . $title . ' <a href="' . $fileUrl . '" download>Download</a></li>';
            }
        }
        ?>
      </ul>
    </section>

    <section id="upload" class="upload">
      <h3>Upload Sermon</h3>
      <?php if ($logged_in): ?>
        <form id="uploadForm" action="upload.php" method="post" enctype="multipart/form-data">
          <label for="sermon">Choose mp3/m4a/ogg/wav file</label>
          <input type="file" id="sermon" name="sermon" accept="audio/*" required>
          <label for="title">Title (optional)</label>
          <input type="text" id="title" name="title" placeholder="Sermon title">
          <button type="submit">Upload</button>
        </form>
        <p class="note">Files are stored in the /sermons folder. Keep uploads to audio files only.</p>
      <?php else: ?>
        <p class="note">Uploads are restricted. <a href="admin/login.php">Log in</a> as admin to upload sermons.</p>
      <?php endif; ?>
    </section>
  </main>

  <footer class="site-footer">
    <div class="container">© Life Way Charismatic Church</div>
  </footer>

  <script src="assets/player.js"></script>
</body>
</html>
