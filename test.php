<?php
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/php-error.log');
error_reporting(E_ALL);

// This fires even on a fatal error that produces a blank page
register_shutdown_function(function () {
    $err = error_get_last();
    if ($err && in_array($err['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        echo "<pre style='background:#fee;padding:15px;border:2px solid red'>";
        echo "FATAL ERROR CAUGHT:\n\n";
        echo "Message: " . $err['message'] . "\n";
        echo "File:    " . $err['file'] . "\n";
        echo "Line:    " . $err['line'] . "\n";
        echo "</pre>";
    }
});

echo "PHP working. Version: " . phpversion() . "<br>";
echo "Now including index...<br>";

require __DIR__ . '/meet/con.php';

echo "<br>Finished including index (no fatal error).";