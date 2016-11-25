<?php

class DevtodevConfig {
    const API_BASE_URL = 'https://api.paymentwall.com/api';
    const API_VERSION = '1';
    private static $instance;
    private $apiBaseUrl = self::API_BASE_URL;
    private $apiKey = '';
    private $mainUserId = '';

    public static function getInstance() {
        if(!isset(self::$instance)) {
            $className = __CLASS__;
            self::$instance = new $className;
        }
        return self::$instance;
    }

    public function getApiVersion() {
        return self::API_VERSION;
    }

    public function getApiBaseUrl() {
        return $this->apiBaseUrl;
    }

    public function setApiBaseUrl($url = '') {
        $this->apiBaseUrl = $url;
    }

    public function getApiKey() {
        return $this->apiKey;
    }

    public function setApiKey($key = '') {
        $this->apiKey = $key;
    }

    public function getMainUserId(){
        return $this->mainUserId;
    }

    public function setMainUserId($mainUserId = ''){
        $this->mainUserId = $mainUserId;
    }

    public function setParams($config = []) {
        if(isset($config['api_key'])) {
            $this->setApiKey($config['api_key']);
        }

        if(isset($config['main_user_id'])) {
            $this->setMainUserId($config['main_user_id']);
        }
    }
}