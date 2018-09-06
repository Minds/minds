<?php

/**
 * Csv Exporter
 *
 * @author Martin Santangelo
 */

namespace Minds\Core\Util;

class CsvExporter
{
    private $file;

    /**
     * Create a file
     *
     * @param mixed $file file path or will output to the standar output by default
     * @return void
     */
    public static function create($file = null)
    {
        return new self($file);
    }

    /**
     * Constructor
     *
     * @param mixed $file file path or will output to the standar output by default
     * @return void
     */
    public function __construct($file = null)
    {
        if ($file) {
            $this->file = fopen($file, 'w');
        } else {
            $this->sendHeaders();
            $this->file = fopen('php://output', 'w');
        }
    }

    /**
     * Close the file
     *
     * @return void
     */
    public function close()
    {
        fclose($this->file);
        return $this;
    }

    /**
     * Add a line to the csv file
     *
     * @param array $data
     * @return void
     */
    public function addLine($data)
    {
        fputcsv($this->file, $data);
        return $this;
    }

    /**
     * Rewind file pointer
     *
     * @return $this
     */
    public function rewind()
    {
        rewind($this->file);
        return $this;
    }

    /**
     * Passthru file
     *
     * @return $this
     */
    public function passthru()
    {
        fpassthru($this->file);
        return $this;
    }

    /**
     * Send headers
     *
     * @param string $name
     * @return void
     */
    public function sendHeaders($name = 'data.csv') {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename='.$name);
        return $this;
    }

}