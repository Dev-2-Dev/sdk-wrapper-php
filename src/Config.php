<?php
namespace Devtodev\StatApi;

/**
 * Class Config
 *
 * @package Devtodev\StatApi
 */
class Config {
    const API_BASE_URL = 'https://api.devtodev.com/stat';
    const API_VERSION = '1.1';
    /**
     * @var
     */
    private static $instance;
    /**
     * @var string
     */
    private $apiBaseUrl = self::API_BASE_URL;
    /**
     * @var string
     */
    private $apiKey = '';
    /**
     * @var string
     */
    private $mainUserId = '';

    /**
     * @return mixed
     */
    public static function getInstance() {
        if(!isset(self::$instance)) {
            $className = __CLASS__;
            self::$instance = new $className;
        }
        return self::$instance;
    }

    /**
     * @return string
     */
    public function getApiVersion() {
        return self::API_VERSION;
    }

    /**
     * @return string
     */
    public function getApiBaseUrl() {
        return $this->apiBaseUrl;
    }

    /**
     * @param string $url
     */
    public function setApiBaseUrl($url = '') {
        $this->apiBaseUrl = $url;
    }

    /**
     * @return string
     */
    public function getApiUrl() {
        return "{$this->getApiBaseUrl()}/v{$this->getApiVersion()}/";
    }

    /**
     * @return string
     */
    public function getApiKey() {
        return $this->apiKey;
    }

    /**
     * @param string $key
     */
    public function setApiKey($key = '') {
        $this->apiKey = $key;
    }

    /**
     * @return string
     */
    public function getMainUserId() {
        return $this->mainUserId;
    }

    /**
     * @param string $mainUserId
     */
    public function setMainUserId($mainUserId = '') {
        $this->mainUserId = $mainUserId;
    }

    /**
     * @param array $config
     */
    public function setParams($config = []) {
        if(isset($config['api_key'])) {
            $this->setApiKey($config['api_key']);
        }

        if(isset($config['main_user_id'])) {
            $this->setMainUserId($config['main_user_id']);
        }
    }
}