<?php

abstract class BaseApiAction {
    private $isValidate = false;
    protected $params = [];
    protected $requestData = [];
    protected $responseData = [];
    protected $errors = [];

    protected function getMainUserId() {
        return DevtodevConfig::getInstance()
            ->getMainUserID();
    }

    protected function getApiBaseUrl() {
        return DevtodevConfig::getInstance()
            ->getApiBaseUrl();
    }

    protected function getApiKey() {
        return DevtodevConfig::getInstance()
            ->getApiKey();
    }

    public function getParams() {
        return $this->params;
    }

    public function setParams($params = []) {
        $this->params = $params;
    }

    protected function getTime() {
        return time();
    }

    protected function validate() {
        $isValidate = true;
        $mainUserId = $this->getMainUserId();
        if(empty($mainUserId))
            throw new DevtodevException("Parameter 'mainUserId' is missing");

        $actionCode = $this->getActionCode();
        if(empty($actionCode))
            throw new DevtodevException("Parameter 'actionCode' is missing");

        return $isValidate;
    }

    protected abstract function getActionCode();

    protected abstract function validateParams();

    protected abstract function buildRequestData();

    public function run() {
        try {
            $this->errors = [];
            $this->isValidate = ($this->validate() && $this->validateParams());
            if($this->isValidate) {
                $this->buildRequestData();
                $request = new ApiRequest();
                $request->send($this->requestData);
            }
            else {
                throw new DevtodevException("No valid params");
            }
        } catch(DevtodevException $e) {
            DevtodevStatApi::appendToErrors($e->getMessage());
        }
    }
}