<?php

class ApiResponse {
    private $responseData = [];

    public function getResponseData() {
        return $this->responseData;
    }

    public function setResponseData($responseData = []) {
        $this->responseData = $responseData;
    }

    public function prepareResponse() {
        $status = (!empty($this->responseData) && isset($this->responseData['status'])) ? $this->responseData['status'] : 0;
        $header = (!empty($this->responseData) && isset($this->responseData['header'])) ? $this->responseData['header'] : [];

        if($status != 200) {
            $errorMessage = (!empty($header) && isset($header['error_message'])) ? $header['error_message'] : '';
            if(!empty($errorMessage))
                throw new DevtodevException($errorMessage, $status);
        }
    }
}