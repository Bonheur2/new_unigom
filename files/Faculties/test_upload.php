<?php
// Test script to check upload functionality
echo "<h2>Server Information</h2>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Upload Max Filesize: " . ini_get('upload_max_filesize') . "<br>";
echo "Post Max Size: " . ini_get('post_max_size') . "<br>";
echo "Max Execution Time: " . ini_get('max_execution_time') . "<br>";

echo "<h2>Directory Permissions</h2>";
$dirs = [
    '../../uploads/',
    '../../uploads/graduation_books/',
    '../../uploads/qr_codes/',
    '.'
];

foreach ($dirs as $dir) {
    echo $dir . ": ";
    if (file_exists($dir)) {
        echo "Exists | ";
        if (is_writable($dir)) {
            echo "Writable";
        } else {
            echo "NOT Writable";
        }
    } else {
        echo "Does NOT Exist";
    }
    echo "<br>";
}

echo "<h2>Required Files</h2>";
$files = [
    '../../meet/con.php',
    'phpqrcode/qrlib.php'
];

foreach ($files as $file) {
    echo $file . ": ";
    if (file_exists($file)) {
        echo "Exists";
    } else {
        echo "NOT Found";
    }
    echo "<br>";
}
?>