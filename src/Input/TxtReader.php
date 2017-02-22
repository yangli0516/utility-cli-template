<?php

namespace UtilityCli\Input;


class TxtReader
{
    private $fileHandler;

    /**
     * TxtReader constructor.
     *
     * @param string $filePath The path of the input text file.
     */
    public function __construct($filePath)
    {
        $this->fileHandler = fopen($filePath, 'r');
    }

    /**
     * Read the file lines into an array.
     *
     * @return array
     */
    public function readLines()
    {
        $lines = [];
        while (($line = fgets($this->fileHandler)) !== false) {
            $lines[] = $line;
        }
        rewind($this->fileHandler);
        return $lines;
    }

    /**
     * Read the file content into a 2-dimension array.
     *
     * @param string $delimiter The delimiter as the column separator.
     * @return array
     */
    public function readTable($delimiter = "\t")
    {
        $table = [];
        while (($line = fgets($this->fileHandler)) !== false) {
            $row = explode($delimiter, $line);
            $table[] = $row;
        }
        rewind($this->fileHandler);
        return $table;
    }

    /**
     * Close the file.
     */
    public function close()
    {
        if (isset($this->fileHandler) && is_resource($this->fileHandler)) {
            fclose($this->fileHandler);
        }
    }

    public function __destruct()
    {
        $this->close();
    }
}
