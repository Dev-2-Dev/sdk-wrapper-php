<?php

class DevtodevException extends Exception {
    public function __construct($message, $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
        $this->message = "Code: '{$code}''. Error text: '{$message}'";
    }

    public function __toString() {
        return __CLASS__ . "Code: '{$this->code}''. Error text: '{$this->message}' \n";
    }
}