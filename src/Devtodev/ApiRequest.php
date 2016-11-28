<?php

class ApiRequest {
    private $responseData = [];

    public function send($requestData = []) {
        $this->request($requestData);
    }

    protected function getUrl() {
        return DevtodevConfig::getInstance()
            ->getApiUrl();
    }

    protected function getApiVersion() {
        return DevtodevConfig::getInstance()
            ->getApiVersion();
    }

    protected function getApiKey() {
        return DevtodevConfig::getInstance()
            ->getApiKey();
    }

    protected function request($requestData = []) {

        $url = "{$this->getUrl()}/?api={$this->getApiKey()}";

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
        $url = "{$this->getUrl()}/?api={$this->getApiKey()}";
        echo $url;
        echo json_encode($this->responseData);
    }
}