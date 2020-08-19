<?php
namespace Devtodev\StatApi\ApiActions;

use Devtodev\StatApi\ApiException;
use Devtodev\StatApi\Config;
use Devtodev\StatApi\ApiClient;
use Devtodev\StatApi\Request;

/**
 * Class BaseApiAction
 *
 * @package Devtodev\StatApi\ApiActions
 */
abstract class BaseApiAction {
    /**
     * @var bool
     */
    private $isValidate = false;
    /**
     * @var array
     */
    protected $params = [];
    /**
     * @var array
     */
    protected $validateParams = [];
    /**
     * @var array
     */
    protected $requestData = [];
    /**
     * @var array
     */
    protected $responseData = [];
    /**
     * @var array
     */
    protected $errors = [];

    /**
     * @return mixed
     */
    protected function getMainUserId() {
        return Config::getInstance()->getMainUserID();
    }

    /**
     * @return mixed
     */
    protected function getPrevMainUserId() {
        return Config::getInstance()->getPrevMainUserId();
    }

    /**
     * @return mixed
     */
    protected function getApiBaseUrl() {
        return Config::getInstance()->getApiBaseUrl();
    }

    /**
     * @return mixed
     */
    protected function getApiKey() {
        return Config::getInstance()->getApiKey();
    }

    /**
     * @return array
     */
    public function getParams() {
        return $this->params;
    }

    /**
     * @param array $params
     */
    public function setParams($params = []) {
        $this->params = $params;
    }

    /**
     * @return int
     */
    protected function getTime() {
        return time();
    }

    /**
     * @return bool
     * @throws ApiException
     */
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

    /**
     * @return string
     */
    protected abstract function getActionCode();

    /**
     * @return bool
     * @throws ApiException
     */
    protected abstract function validateParams();

    /**
     * @return void
     */
    protected abstract function buildRequestData();

    /**
     * @return array
     */
    protected function buildBaseRequestData() {
        $request = [];
        $prevMainUserId = $this->getPrevMainUserId();
        if(!empty($prevMainUserId)) {
            $request['prev'] = $prevMainUserId;
        }
        return $request;
    }

    /**
     * Prepare and send data
     */
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
            ApiClient::appendToErrors($e->getMessage());
        }
    }
}