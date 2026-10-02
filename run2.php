<?php
exit();
// Scan function: Get all files recursively with their last modified time
function getModifiedFiles($dir) {
    $files = [];

    $rii = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS)
    );

    foreach ($rii as $file) {
        if ($file->isFile()) {
            $files[] = [
                'path' => str_replace(getcwd() . '/', '', $file->getPathname()),
                'mtime' => filemtime($file->getPathname())
            ];
        }
    }

    // Sort by modification time descending
    usort($files, fn($a, $b) => $b['mtime'] <=> $a['mtime']);

    return $files;
}

// Fetch and format data
$files = getModifiedFiles(__DIR__);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Recently Modified Files</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f4f4f8;
      color: #333;
      padding: 40px;
    }
    h1 {
      text-align: center;
      margin-bottom: 30px;
      color: #555;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      background: #fff;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    th, td {
      padding: 12px 18px;
      border-bottom: 1px solid #eee;
      text-align: left;
    }
    th {
      background: #007BFF;
      color: #fff;
      text-transform: uppercase;
      font-size: 14px;
    }
    tr:hover {
      background-color: #f1f1f1;
    }
    .path {
      font-family: monospace;
    }
  </style>
</head>
<body>
  <h1>Recently Modified Files</h1>
  <table>
    <thead>
      <tr>
        <th>#</th>
        <th>File Path</th>
        <th>Last Modified</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($files as $index => $file): ?>
        <tr>
          <td><?= $index + 1 ?></td>
          <td class="path"><?= htmlspecialchars($file['path']) ?></td>
          <td><?= date("Y-m-d H:i:s", $file['mtime']) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</body>
</html>
