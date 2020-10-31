<?php
namespace Orzorc\Image2Excel\Exception;

use Exception;
use Throwable;

class UnsupportedTypeException extends Exception {
    private $unsupportedType = '';

    /**
     * @return string
     */
    public function getUnsupportedType() {
        return $this->unsupportedType;
    }

    /**
     * @param string $unsupportedType
     * @return UnsupportedTypeException
     */
    public function setUnsupportedType($unsupportedType) {
        $this->unsupportedType = $unsupportedType;
        return $this;
    }

    public function __construct($unsupportedType, $code = 0, Throwable $previous = null) {
        $this->setUnsupportedType($unsupportedType);
        $message = 'Type "' . $unsupportedType . '" is not supported';
        parent::__construct($message, $code, $previous);
    }
}
