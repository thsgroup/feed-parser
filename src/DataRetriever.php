<?php

namespace Thsgroup\FeedParser;

use Thsgroup\FeedParser\Validator\ValidatorFiles;

class DataRetriever
{
    private $directory;

    public function __construct($directory)
    {
        $this->directory = $directory;
        $this->prepareDirectory();
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
        $validatorFiles = new ValidatorFiles();

        if (!is_array($files)) {
            $files = array($files);
        }

        foreach ($files as $file) {
            if ($validatorFiles->isStringUrl($file)) {
                $this->downloadFile($file);
            } else {
                $this->moveFile($file);
            }
        }
    }

    private function moveFile($file)
    {
        if (!rename(realpath($file), $this->directory . DIRECTORY_SEPARATOR . basename($file))) {
            throw new \RuntimeException('File could not be moved: ' . basename($file));
        }
    }

    private function downloadFile($file)
    {
        if (!file_put_contents($this->directory . DIRECTORY_SEPARATOR . basename($file), fopen($file, 'rb'))) {
            throw new \RuntimeException('File could not be downloaded: ' . $file);
        }
    }
}
