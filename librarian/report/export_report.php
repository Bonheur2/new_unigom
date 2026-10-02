<?php
if (isset($_POST['csv'])) {
    // Get the data
    $csv = $_POST['csv'];

    // Generate a unique filename
    $filename = 'Books report' . '.csv';

    // Save the file
    file_put_contents($filename, $csv);

    // Return the filename
    echo $filename;
    exit;
} elseif (isset($_GET['file'])) {
    // Download the file
    $filename = $_GET['file'];
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    readfile($filename);
    exit;
}
?>
