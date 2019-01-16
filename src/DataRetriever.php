<?php

namespace Thsgroup\FeedParser;

use Thsgroup\FeedParser\Validator\ValidatorFiles;

class DataRetriever
{
    private $directory;
    private $storedFiles;

    public function __construct($directory)
    {
        $this->directory = $directory;
        $this->prepareDirectory();
        $this->storedFiles = array();
    }

    /**
     * @return bool
     * @throws \RuntimeException
     */
    public function prepareDirectory()
    {
        if (!file_exists($this->directory) && !@mkdir($newDirectory = $this->directory, 0777, true) && !is_dir($newDirectory)) {
            throw new \RuntimeException('Directory: ' . $newDirectory . ' could not be created');
        }

        return true;
    }

    /**
     * @param string $path
     */
    public function removeDirectory($path)
    {
        $files = glob($path . '/*');
        foreach ($files as $file) {
            is_dir($file) ? $this->removeDirectory($file) : unlink($file);
        }
        if (file_exists($path)) {
            rmdir($path);
        }
    }

    public function storeFiles($files)
    {
        if (!is_array($files)) {
            $files = array($files);
        }

        foreach ($files as $file) {
            $this->storeFile($file);
        }
    }

    /**
     * @return array
     */
    public function getStoredFiles()
    {
        return $this->storedFiles;
    }

    private function storeFile($file)
    {
        $validatorFiles = new ValidatorFiles();

        if ($validatorFiles->isStringUrl($file)) {
            $this->storedFiles[] = $this->downloadFile($file);
        } else {
            $this->storedFiles[] = $this->moveFile($file);
        }
    }

    private function moveFile($file)
    {
        if (!@copy(realpath($file), $this->directory . DIRECTORY_SEPARATOR . basename($file))) {
            throw new \RuntimeException('File could not be moved: ' . realpath($file));
        }

        return $this->directory . DIRECTORY_SEPARATOR . basename($file);
    }

    private function downloadFile($file)
    {
        if (!file_put_contents($this->directory . DIRECTORY_SEPARATOR . basename($file), @fopen($file, 'rb'))) {
            throw new \RuntimeException('File could not be downloaded: ' . $file);
        }

        return $this->directory . DIRECTORY_SEPARATOR . basename($file);
    }
}
