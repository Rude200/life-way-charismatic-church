<?php
session_start();
require_once __DIR__ . '/config.php';

// Only allow logged-in admin to upload
if (empty($_SESSION['logged_in'])) {
    http_response_code(403);
    header('Location: index.php?msg=' . urlencode('Please login to upload.'));
    exit;
}

$target_dir = __DIR__ . '/sermons/';

// Create folder if not existing
if(!is_dir($target_dir)){
    mkdir($target_dir, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_FILES['sermon'])) {
        header('Location: index.php?msg=' . urlencode('No file uploaded.'));
        exit;
    }

    $file = $_FILES['sermon'];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        header('Location: index.php?msg=' . urlencode('Upload error: ' . $file['error']));
        exit;
    }

    $maxSize = 50 * 1024 * 1024; // 50MB
    if ($file['size'] > $maxSize) {
        header('Location: index.php?msg=' . urlencode('File too large. Limit is 50MB.'));
        exit;
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    $allowed = ['audio/mpeg','audio/mp3','audio/x-m4a','audio/mp4','audio/m4a','audio/ogg','audio/wav','audio/x-wav'];
    if (!in_array($mime, $allowed)) {
        header('Location: index.php?msg=' . urlencode('Invalid file type: ' . $mime));
        exit;
    }

    $safeName = preg_replace('/[^A-Za-z0-9._-]/', '_', basename($file['name']));
    if (!empty($_POST['title'])) {
        $title = preg_replace('/[^A-Za-z0-9._-]/', '_', substr($_POST['title'],0,60));
        $safeName = $title . '_' . $safeName;
    }

    $target_file = $target_dir . $safeName;

    // Avoid overwriting existing file
    $i = 1;
    $base = pathinfo($safeName, PATHINFO_FILENAME);
    $ext = pathinfo($safeName, PATHINFO_EXTENSION);
    while (file_exists($target_file)) {
        $target_file = $target_dir . $base . '_' . $i . ($ext ? '.' . $ext : '');
        $i++;
    }

    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        header('Location: index.php?msg=' . urlencode('Upload successful: ' . basename($target_file)));
        exit;
    } else {
        header('Location: index.php?msg=' . urlencode('Error moving uploaded file.'));
        exit;
    }
} else {
    header('Location: index.php');
    exit;
}
?>