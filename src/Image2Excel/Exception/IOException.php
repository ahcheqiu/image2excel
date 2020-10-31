<?php
namespace Orzorc\Image2Excel\Exception;

use Exception;
use Throwable;

class IOException extends Exception {
    private $filePath = '';

    /**
     * @return string
     */
    public function getFilePath() {
        return $this->filePath;
    }

    /**
     * @param string $filePath
     * @return IOException
     */
    public function setFilePath($filePath) {
        $this->filePath = $filePath;
        return $this;
    }

    public function __construct($message, $filePath, Throwable $previous = null) {
        $this->setFilePath($filePath);
        $message = '处理文件[' . $filePath . ']时发生错误：' . $message;
        parent::__construct($message, 0, $previous);
    }
}
