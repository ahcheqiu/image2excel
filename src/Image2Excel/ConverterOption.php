<?php

namespace Orzorc\Image2Excel;

class ConverterOption {
    const OPTION_SUPPORTED_IMAGE_TYPE = 'supportedImageTypes';

    const OPTION_RE_SAMPLE = 'reSample';

    const OPTION_MAX_HEIGHT = 'maxHeight';

    const OPTION_MAX_WIDTH = 'maxWidth';

    /**
     * @var int[]
     */
    private $supportedImageTypes = [];

    private $reSample = false;

    private $maxHeight = 0;

    private $maxWidth = 0;

    /**
     * @return int
     */
    public function getMaxHeight() {
        return $this->maxHeight;
    }

    /**
     * @param int $maxHeight
     * @return ConverterOption
     */
    public function setMaxHeight(int $maxHeight) {
        $this->maxHeight = $maxHeight;
        return $this;
    }

    /**
     * @return int
     */
    public function getMaxWidth() {
        return $this->maxWidth;
    }

    /**
     * @param int $maxWidth
     * @return ConverterOption
     */
    public function setMaxWidth(int $maxWidth) {
        $this->maxWidth = $maxWidth;
        return $this;
    }

    /**
     * @return bool
     */
    public function shouldReSample() {
        return $this->reSample;
    }

    /**
     * @param bool $reSample
     * @return ConverterOption
     */
    public function setReSample(bool $reSample) {
        $this->reSample = $reSample;
        return $this;
    }

    /**
     * @return int[]
     */
    public function getSupportedImageTypes() {
        return $this->supportedImageTypes;
    }

    /**
     * @param int[] $supportedImageTypes
     * @return ConverterOption
     */
    public function setSupportedImageTypes(array $supportedImageTypes) {
        $this->supportedImageTypes = $supportedImageTypes;
        return $this;
    }

    public function __construct($options = []) {
        $defaults = $this->defaults();
        $options = array_merge($defaults, $options);
        $this->applyOptions($options);
    }

    public function applyOptions($options) {
        foreach($options as $type => $values) {
            $method = 'set' . ucfirst($type);
            if(method_exists($this, $method)) {
                $this->$method($values);
            }
        }
    }

    public function defaults() {
        return [
            self::OPTION_SUPPORTED_IMAGE_TYPE => [
                IMAGETYPE_BMP,
                IMAGETYPE_GIF,
                IMAGETYPE_JPEG,
                IMAGETYPE_PNG,
                IMAGETYPE_WBMP,
                IMAGETYPE_WEBP,
                IMAGETYPE_XBM
            ],
            self::OPTION_RE_SAMPLE => true,
            self::OPTION_MAX_HEIGHT => 120,
            self::OPTION_MAX_WIDTH => 120
        ];
    }
}
