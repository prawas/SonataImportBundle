<?php

namespace Doctrs\SonataImportBundle\Loaders;


use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Process\Exception\InvalidArgumentException;

class CsvFileLoader implements FileLoaderInterface {

    const BOM = "\xef\xbb\xbf";

    /** @var File $file  */
    protected $file = null;

    public function setFile(File $file) : FileLoaderInterface {
        $this->file = $file;
        return $this;
    }

    public function getIteration() {
        if (!$this->file) {
            throw new InvalidArgumentException('File not found');
        }

        $file = fopen($this->file->getRealPath(), 'r');

        // Progress file pointer and get first 3 characters to compare to the BOM string.
        if (fgets($file, 4) !== self::BOM) {
            // BOM not found - rewind pointer to start of file.
            rewind($file);
        }

        while (($line = fgetcsv($file, 0, ';')) !== false) {
            yield $line;
        }
    }

}
