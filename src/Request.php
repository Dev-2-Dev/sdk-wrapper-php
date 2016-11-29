<?php
namespace Devtodev\StatApi;

class Request {
    private $responseData = [];

    public function send($requestData = []) {
        try {
            $this->sendRequest($requestData);
        } catch(ApiException $e) {
            StatApi::appendToErrors($e->getMessage());
        }
    }

    protected function getUrl() {
        return Config::getInstance()
            ->getApiUrl();
    }

    protected function getApiVersion() {
        return Config::getInstance()
            ->getApiVersion();
    }

    protected function getApiKey() {
        return Config::getInstance()
            ->getApiKey();
    }

    protected function sendRequest($requestData = []) {

        $url = "{$this->getUrl()}?api={$this->getApiKey()}";

        $curlHandle = curl_init($url);
        curl_setopt($curlHandle, CURLOPT_HTTPHEADER, array('Content-Type: text/plain;charset=UTF-8'));
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlHandle, CURLOPT_POST, true);
        curl_setopt($curlHandle, CURLOPT_USERAGENT, "Devtodev api PHP client version {$this->getApiVersion()}");
        curl_setopt($curlHandle, CURLOPT_POSTFIELDS, gzencode(json_encode($requestData)));
        curl_setopt($curlHandle, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curlHandle, CURLOPT_ENCODING, 'gzip');
        $response = curl_exec($curlHandle);

        $headerSize = curl_getinfo($curlHandle, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $headerSize);
        $body = substr($response, $headerSize);

        $this->responseData = [
            'header' => $header,
            'body' => $body,
            'status' => curl_getinfo($curlHandle, CURLINFO_HTTP_CODE)
        ];
        curl_close($curlHandle);

        $this->prepareResponse();
    }

    protected function prepareResponse() {
        $response = new Response();
        $response->setResponseData($this->responseData);
        $response->prepareResponse();
    }
}