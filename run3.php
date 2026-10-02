<?php

exit();
function listHiddenFiles($dir, $basePath = '') {
    $rii = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );

    foreach ($rii as $file) {
        if ($file->isFile()) {
            $filename = $file->getFilename();
            
                $arra_ignore = [
                    '.babelrc.esm.json',
                    '.babelrc.json',
                    '.codeclimate.yml',
                    '.csslintrc',
                    '.eslintignore',
                    '.eslintrc.js',
                    '.gitpod.yml',
                    '.lgtm.yml',
                    '.prettierignore',
                    '.prettierrc',
                    '.sauce.yml',
                ];

            // Check if file is hidden and not .htaccess or .gitignore
            if ((substr($filename, 0, 1) === '.' && $filename !== '.htaccess' && $filename !== '.gitignore' && !in_array($filename, $arra_ignore)) || $filename === 'error_log') {
                $relativePath = ltrim(str_replace($basePath, '', $file->getPathname()), '/\\');
                $size = filesize($file->getPathname());

                // delete this file 
                // Uncomment the line below to actually delete the file
                echo "{$relativePath} - {$size} bytes\n";
                unlink($file->getPathname());
            }
        }
    }
}

$startDir = __DIR__;
listHiddenFiles($startDir, $startDir);
