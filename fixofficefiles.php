<?php

/*
* extract xml documents of $file into temp location
* using xpath to crawl through the xml remove metadata
*
*
*/
class FixOfficeFiles
{
    protected $files = array();
    protected $destination = dirname(__FILE__) . '/temp';
    protected $source = dirname(__FILE__) . '/files';
    /*
    * Constructor, automaticall called when class is instantiated
    * if array of files is passed, will add files to be cleaned
    *
    * @param array $files
    * @return void
    */
    public function __construct(array $files = array())
    {
        if (is_array($files) && !empty($files)) {
            foreach ($files as $file)
            {
                if (file_exists($file)) {
                    $this->files[] = $file;
                }
            }
        }
    }
    
    /**
     * Adds file to files array to be cleaned
     *
     * @param mixed $files (allows either string or array)
     * @return FixOfficeFiles (we do this so we can chain
     */
    public function add($files)
    {
        // Add just one file
        if (!is_array($file)) {
            $files = array($files);
        }
        // Add an array of files
        foreach ($files as $file) {
           if (!empty($file) && file_exists($file)) {
               $this->files[] = $file;
            }
        }
        // Return this class so we can Chain Events
         return $this;
     }

    /**
     * Iterates over and cleans every file in $this->files
     *
     * @return void
     */
    public function cleanFiles()
    {   
        $this->zip();
       // $this->unzip();

        if (!empty($this->files)) {
            foreach ($files as $file) {
                   $this->clean($file);
            }
        }
    }

    public function clean($file)
    {   /* Todo Use xpath to crawl through and remove metadata */
         
    }

    public function createfilelist()
    {  
        $iterator = new DirectoryIterator($source);
        foreach ($iterator as $file)
        {
            if (!$file->isDot()) {
                if ($file->isDir()) {
                    $subDirectory = $source . '/' . $file->getFilename();
                    $this->processDirectory($subDirectory);
                }
                if ($file->isFile()) {
                    $filename = $source . '/' . $file->getFilename();
                    $this->files[] = $filename;
                 }
            }
         }
    }

    public function zip()
    {
        $zip = new ZipArchive();
        $zipfile = tempnam("tmp","zip");
        $res = $zip->open($zipfile,ZipArchive::OVERWRITE);
        if ($res !== TRUE) { die("Could not create archive"); }

        foreach ($files as $file)
        {
            $zip->addFile($file,$file);
        }
        $zip->close();
    }

    public function unzip() 
    {
        // create object
        $zip = new ZipArchive();
        $zipfile = tempnam("tmp","zip");
        // open archive
        if ($zip->open($zipfile) !== TRUE) {
            die ("Could not open archive");
        }
        // extract contents to destination directory
        $zip->extractTo($destination);
        // close archive
        $zip->close();
        echo 'Archive extracted to directory';
    }
}
?>
