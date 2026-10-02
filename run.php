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
            /*
            files to ignore
            librarian\books\jsPDF\.babelrc.esm.json - 85 bytes
                librarian\books\jsPDF\.babelrc.json - 68 bytes
                librarian\books\jsPDF\.codeclimate.yml - 265 bytes
                librarian\books\jsPDF\.csslintrc - 107 bytes
                librarian\books\jsPDF\.eslintignore - 86 bytes
                librarian\books\jsPDF\.eslintrc.js - 497 bytes
                librarian\books\jsPDF\.gitpod.yml - 116 bytes
                librarian\books\jsPDF\.lgtm.yml - 49 bytes
                librarian\books\jsPDF\.prettierignore - 86 bytes
                librarian\books\jsPDF\.prettierrc - 3 bytes
                librarian\books\jsPDF\.sauce.yml - 102 bytes
                librarian\books\jsPDF\test\.eslintrc.js - 119 bytes
            */
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

            // Check if file is hidden and not .htaccess
            if (substr($filename, 0, 1) === '.' && $filename !== '.htaccess' && $filename !== '.gitignore' && !in_array($filename, $arra_ignore)) {
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
