<?php
namespace Devtodev\StatApi\ApiAction;
use Devtodev\StatApi\ApiException;
use Devtodev\StatApi\Config;
use Devtodev\StatApi\StatApi;
use Devtodev\StatApi\Request;

abstract class BaseApiAction {
    private $isValidate = false;
    protected $params = [];
    protected $validateParams = [];
    protected $requestData = [];
    protected $responseData = [];
    protected $errors = [];

    protected function getMainUserId() {
        return Config::getInstance()
            ->getMainUserID();
    }

    protected function getApiBaseUrl() {
        return Config::getInstance()
            ->getApiBaseUrl();
    }

    protected function getApiKey() {
        return Config::getInstance()
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
            throw new ApiException("Parameter 'mainUserId' is missing");

        $actionCode = $this->getActionCode();
        if(empty($actionCode))
            throw new ApiException("Parameter 'actionCode' is missing");

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
                $request = new Request();
                $request->send($this->requestData);
            }
            else {
                throw new ApiException("No valid params");
            }
        } catch(ApiException $e) {
            StatApi::appendToErrors($e->getMessage());
        }
    }
}