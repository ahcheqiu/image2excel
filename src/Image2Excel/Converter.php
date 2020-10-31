<?php

namespace Orzorc\Image2Excel;

use Box\Spout\Writer\Exception\WriterNotOpenedException;
use Orzorc\Image2Excel\Exception\IOException;
use Orzorc\Image2Excel\Exception\UnsupportedTypeException;

class Converter {
    /**
     * @var InputImage
     */
    private $image = null;

    /**
     * @var OutputExcel
     */
    private $excel = null;

    /**
     * @var ConverterOption
     */
    private $options = null;

    /**
     * @return InputImage
     */
    public function getImage() {
        return $this->image;
    }

    /**
     * @param InputImage $image
     * @return Converter
     */
    public function setImage(InputImage $image) {
        $this->image = $image;
        return $this;
    }

    /**
     * @return OutputExcel
     */
    public function getExcel() {
        return $this->excel;
    }

    /**
     * @param OutputExcel $excel
     * @return Converter
     */
    public function setExcel(OutputExcel $excel) {
        $this->excel = $excel;
        return $this;
    }

    /**
     * @return ConverterOption
     */
    public function getOptions() {
        return $this->options;
    }

    /**
     * @param ConverterOption $options
     * @return Converter
     */
    public function setOptions(ConverterOption $options) {
        $this->options = $options;
        return $this;
    }

    /**
     * @throws IOException
     * @throws UnsupportedTypeException
     * @throws \Box\Spout\Common\Exception\IOException
     * @throws WriterNotOpenedException
     */
    public function run() {
        $image = $this->getImage();
        $type = $image->getType();
        if(!$this->isSupportedType($type)) {
            throw new UnsupportedTypeException($type);
        }

        $img = $image->getResource();
        if($this->shouldReSample()) {
            $img = $this->reSample($img, $this->getOptions()->getMaxWidth(), $this->getOptions()->getMaxHeight());
        }

        $width = imagesx($img);
        $height = imagesy($img);
        $excel = $this->getExcel();
        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $rgb = imagecolorat($img, $x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;
                $excel->write($r, $g, $b);
            }
            $excel->newLine();
        }
    }

    /**
     * @param string $type
     * @return bool
     * @throws IOException
     */
    public function isSupportedType($type = '') {
        if(empty($type)) {
            $type = $this->getImage()->getType();
        }

        return in_array($type, $this->getOptions()->getSupportedImageTypes());
    }

    /**
     * 是否需要重新采样压缩
     * @return bool
     */
    public function shouldReSample() {
        return $this->getOptions()->shouldReSample();
    }

    /**
     * @param resource $img
     * @param int $maxWidth
     * @param int $maxHeight
     * @return resource
     */
    public function reSample($img, int $maxWidth, int $maxHeight) {
        $originHeight = imagesy($img);
        $originWidth = imagesx($img);
        $heightZoomRate = $this->getZoomRate($originHeight, $maxHeight);
        $widthZoomRate = $this->getZoomRate($originWidth, $maxWidth);
        $maxZoomRate = max($heightZoomRate, $widthZoomRate);
        if ($maxZoomRate > 1) {
            $newWidth = ceil($originWidth / $maxZoomRate);
            $newHeight = ceil($originHeight / $maxZoomRate);
            $newImage = imagecreatetruecolor($newWidth, $newHeight);
            imagecopyresampled($newImage, $img, 0, 0, 0, 0, $newWidth, $newHeight, $originWidth, $originHeight);
        } else {
            $newImage = $img;
        }
        return $newImage;
    }

    protected function getZoomRate($origin, $max) {
        if ($origin > $max) {
            return ceil(($origin / $max) * pow(10, 6)) / pow(10, 6);
        } else {
            return 1;
        }
    }
}
