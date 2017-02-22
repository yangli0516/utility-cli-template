<?php

namespace UtilityCli\Output;

/**
 * Class TxtWriter
 *
 * Used to output data into text files.
 *
 * @package UtilityCli\Output
 */
class TxtWriter
{
    private $fileHandler;

    /**
     * TxtWriter constructor.
     *
     * @param string $filePath The path of the output text file.
     */
    public function __construct($filePath)
    {
        $this->fileHandler = fopen($filePath, 'w');
    }

    /**
     * Write a line into the file.
     *
     * @param string $text The content of the line.
     */
    public function writeLine($text = '')
    {
        fwrite($this->fileHandler, $text . PHP_EOL);
    }

    /**
     * Write a number of lines into the file.
     *
     * @param array $lines An array of strings containing the content.
     */
    public function writeLines(array $lines)
    {
        foreach ($lines as $line) {
            $this->writeLine($line);
        }
    }

    /**
     * Write an array into a table format.
     *
     * @param array $data A two dimension array contains the rows and columns
     *   of the table. The keys of the first row are used as the column headers.
     * @param string $title Table caption.
     */
    public function writeTable(array $data, $title = null)
    {
        if (isset($title)) {
            $this->writeLine('Table: ' . $title);
        }
        $this->writeLine(str_repeat('-', 120));
        // Write Header
        if (count($data) > 0) {
            $this->writeLine(implode("\t", array_keys($data[0])));
            $this->writeLine(str_repeat('-', 120));
        }
        // Write values
        foreach ($data as $row) {
            $this->writeLine(implode("\t", array_values($row)));
        }
    }

    /**
     * Write an array into the CSV format.
     *
     * @param array $data A two dimension array contains the rows and columns
     *   of the table. The keys of the first row are used as the column headers.
     */
    public function writeCSV(array $data)
    {
        if (count($data) > 0) {
            $this->writeCSVLine(array_keys($data[0]));
            foreach ($data as $line) {
                $this->writeCSVLine($line);
            }
        }
    }

    /**
     * Write an array as a row of a CSV file.
     *
     * @param array $line The values of each cell of the row.
     */
    public function writeCSVLine(array $line)
    {
        $cells = [];
        foreach ($line as $cell) {
            $cell = str_replace('"', '""', $cell);
            $cells[] = '"' . $cell . '"';
        }
        $this->writeLine(implode(',', $cells));
    }

    /**
     * Write an array into the tree format.
     *
     * The tree array should be in a nested structure that each node should have
     * the node value under key 'value'. And if the node has children, all the
     * children nodes are under key 'nodes'.
     *
     * @param array $tree The tree array.
     * @param string $indentChar The character used for indentation.
     * @param int $indentLevel The number of characters used for each indentation.
     */
    public function writeTree(array $tree, $indentChar = ' ', $indentLevel = 4)
    {
        foreach ($tree as $node) {
            $this->writeNode($node, 0, $indentChar, $indentLevel);
        }
    }

    /**
     * Write a tree node.
     *
     * @param array $node
     * @param integer $level
     * @param array $indentChar
     * @param integer $indentLevel
     */
    private function writeNode($node, $level, $indentChar, $indentLevel)
    {
        $this->writeLine(str_repeat($indentChar, $level * $indentLevel) . $node['value']);
        if (isset($node['nodes']) && count($node['nodes']) > 0) {
            foreach ($node['nodes'] as $subnode) {
                $this->writeNode($subnode, $level + 1, $indentChar, $indentLevel);
            }
        }
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