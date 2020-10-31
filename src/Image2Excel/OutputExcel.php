<?php

namespace Orzorc\Image2Excel;

use Box\Spout\Common\Entity\Cell;
use Box\Spout\Common\Entity\Style\Color;
use Box\Spout\Common\Exception\IOException;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Writer\Exception\WriterNotOpenedException;
use Box\Spout\Writer\XLSX\Writer;

class OutputExcel {
    private $path = '';

    /**
     * @var Cell[]
     */
    private $cells = [];

    /**
     * @var Writer
     */
    private $writer = null;

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     * @return OutputExcel
     */
    public function setPath(string $path): OutputExcel
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @param string $path
     */
    public function __construct(string $path) {
        $this->setPath($path);
    }

    /**
     * @param int $r
     * @param int $g
     * @param int $b
     */
    public function write(int $r, int $g, int $b) {
        $style = (new StyleBuilder())->setBackgroundColor(Color::rgb($r, $g, $b))
            ->setShouldWrapText(true)
            ->build();
        $this->cells[] = WriterEntityFactory::createCell(' ', $style);
    }

    /**
     * @throws IOException
     * @throws WriterNotOpenedException
     */
    public function newLine() {
        $row = WriterEntityFactory::createRow($this->cells);
        $this->cells = [];
        $this->loadActiveWriter()->addRow($row);
    }

    /**
     * @return Writer
     * @throws IOException
     */
    public function loadActiveWriter() {
        if($this->writer == null) {
            $this->writer = WriterEntityFactory::createXLSXWriter();
            $this->writer->openToFile($this->getPath());
        }

        return $this->writer;
    }

    public function close() {
        if($this->writer != null) {
            $this->writer->close();
            $this->writer = null;
        }
    }
}
