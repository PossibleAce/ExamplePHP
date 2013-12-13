<?php

class CleanMetaData {
    
    public function clean()
    {
        $directory = dirname(__FILE__) . '/files';
        $this->processDirectory($directory);
    }

    private function processDirectory($directory)
    {
        $iterator = new DirectoryIterator($directory);
        foreach ($iterator as $file)
        {
            if (!$file->isDot()) {
                if ($file->isDir()) {
                    $subDirectory = $directory . '/' . $file->getFilename();
                    $this->processDirectory($subDirectory);
                }

                if ($file->isFile()) {
                    $filename = $directory . '/' . $file->getFilename();
                    echo 'Cleaning File: ' . $filename . "\n";
                    exec('/usr/local/bin/exiftool -overwrite_original -all:all= ' . $filename . ' 2>&1');
                 }
            }
         }
    }
}

$cleaner = new CleanMetaData();
$cleaner->clean();
echo 'Cleaning Complete.';
