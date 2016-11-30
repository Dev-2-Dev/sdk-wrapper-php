<?php
namespace Devtodev\StatApi;

/**
 * Class Response
 *
 * @package Devtodev\StatApi
 */
class Response {
    /**
     * @var array
     */
    private $responseData = [];

    /**
     * @return array
     */
    public function getResponseData() {
        return $this->responseData;
    }

    /**
     * @param array $responseData
     */
    public function setResponseData($responseData = []) {
        $this->responseData = $responseData;
    }

    /**
     * @throws ApiException
     */
    public function prepareResponse() {
        $status = (!empty($this->responseData) && isset($this->responseData['status'])) ? $this->responseData['status'] : 0;
        $header = (!empty($this->responseData) && isset($this->responseData['header'])) ? json_decode($this->responseData['header'], true) : [];

        if($status != 200) {
            $errorMessage = (!empty($header) && isset($header['error_message'])) ? $header['error_message'] : 'Bad response status';
            throw new ApiException($errorMessage, $status);
        }
    }
}