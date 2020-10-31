<?php

namespace Orzorc\Image2Excel;

use Orzorc\Image2Excel\Exception\IOException;
use Orzorc\Image2Excel\Exception\UnsupportedTypeException;

class InputImage {
    private $path = '';

    private $type = 0;

    /**
     * @var resource
     */
    private $resource = null;

    /**
     * @return resource
     * @throws IOException
     * @throws UnsupportedTypeException
     */
    public function getResource() {
        if(empty($this->resource)) {
            $this->loadResource();
        }
        return $this->resource;
    }

    /**
     * @param resource $resource
     * @return InputImage
     */
    public function setResource($resource) {
        $this->resource = $resource;
        return $this;
    }

    /**
     * 获取图片类型
     * @return int
     * @throws IOException
     */
    public function getType() {
        if(empty($this->type)) {
            $this->loadType();
        }
        return $this->type;
    }

    /**
     * @param int $type
     * @return InputImage
     */
    public function setType($type) {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getPath() {
        return $this->path;
    }

    /**
     * @param string $path
     * @return InputImage
     */
    public function setPath($path) {
        $this->path = $path;
        return $this;
    }

    /**
     * InputImage constructor
     * @param string $path 请自行检查图片的合法性，预防安全风险
     * @see https://www.php.net/manual/en/function.getimagesize
     */
    public function __construct($path) {
        $this->setPath($path);
    }

    /**
     * 从图片路径加载图片类型
     * @throws IOException
     */
    public function loadType() {
        $filePath = $this->getPath();
        $info = getimagesize($filePath);
        if(empty($info)) {
            throw new IOException('无法获取图片信息', $filePath);
        }
        $this->setType(intval($info[2]));
    }

    /**
     * @throws IOException
     * @throws UnsupportedTypeException
     */
    public function loadResource() {
        $filePath = $this->getPath();
        $type = $this->getType();
        switch ($type) {
            case IMAGETYPE_BMP:
                $img = imagecreatefrombmp($filePath);
                break;
            case IMAGETYPE_GIF:
                $img = imagecreatefromgif($filePath);
                break;
            case IMAGETYPE_JPEG:
                $img = imagecreatefromjpeg($filePath);
                break;
            case IMAGETYPE_PNG:
                $img = imagecreatefrompng($filePath);
                break;
            case IMAGETYPE_WBMP:
                $img = imagecreatefromwbmp($filePath);
                break;
            case IMAGETYPE_WEBP:
                $img = imagecreatefromwebp($filePath);
                break;
            case IMAGETYPE_XBM:
                $img = imagecreatefromxbm($filePath);
                break;
            default:
                throw new UnsupportedTypeException($type);
        }
        if(!$img) {
            throw new IOException('无法生成图片资源', $filePath);
        }

        $this->setResource($img);
    }
}
