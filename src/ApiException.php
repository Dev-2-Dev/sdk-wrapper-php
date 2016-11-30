<?php
namespace Devtodev\StatApi;

/**
 * Class ApiException
 *
 * @package Devtodev\StatApi
 */
class ApiException extends \Exception {
    /**
     * ApiException constructor.
     *
     * @param string $message
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct($message, $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
        $this->message = "Code: '{$code}''. Error text: '{$message}'";
    }

    /**
     * @return string
     */
    public function __toString() {
        return __CLASS__ . "Code: '{$this->code}''. Error text: '{$this->message}' \n";
    }
}