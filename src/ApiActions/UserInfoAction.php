<?php

namespace Devtodev\StatApi\ApiActions;

use Devtodev\StatApi\ApiClient;

/**
 * Class UserInfoAction
 *
 * @package Devtodev\StatApi\ApiActions
 */
final class UserInfoAction extends BaseApiAction {
    /**
     * @var string
     */
    protected $country = '';
    /**
     * @var string
     */
    protected $language = '';
    /**
     * @var string
     */
    protected $ip = '';
    /**
     * @var string
     */
    protected $carrier = '';
    /**
     * @var int
     */
    protected $isRooted = 0;
    /**
     * @var string
     */
    protected $userAgent = '';
    
    
    /**
     * @return string
     */
    public function getCountry() {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry($country) {
        $this->country = (string) $country;
    }

    /**
     * @return string
     */
    public function getLanguage() {
        return $this->language;
    }

    /**
     * @param string $language
     */
    public function setLanguage($language) {
        $this->language = (string) $language;
    }

    /**
     * @return string
     */
    public function getIp() {
        return $this->ip;
    }

    /**
     * @param string $ip
     */
    public function setIp($ip) {
        $this->ip = (string) $ip;
    }

    /**
     * @return string
     */
    public function getCarrier() {
        return $this->carrier;
    }

    /**
     * @param string $carrier
     */
    public function setCarrier($carrier) {
        $this->carrier = (string) $carrier;
    }

    /**
     * @return int
     */
    public function getIsRooted() {
        return $this->isRooted;
    }

    /**
     * @param int $isRooted
     */
    public function setIsRooted($isRooted) {
        $this->isRooted = (int) $isRooted;
    }

    /**
     * @return string
     */
    public function getUserAgent() {
        return $this->userAgent;
    }

    /**
     * @param string $userAgent
     */
    public function setUserAgent($userAgent) {
        $this->userAgent = (string) $userAgent;
    }

    /**
     * @return string
     */
    private function getLocale() {
        $locale = '';
        if(!empty($_SERVER['HTTP_ACCEPT_LANGUAGE']))
            $locale = \Locale::acceptFromHttp($_SERVER['HTTP_ACCEPT_LANGUAGE']);
        return $locale;
    }

    /**
     * @return string
     */
    private function getCountryFromLocale() {
        $country = '';
        $locale = $this->getLocale();
        if(!empty($locale)) {
            $country = \Locale::getRegion($locale);
        }

        return $country;
    }

    /**
     * @return string
     */
    private function getLanguageFromLocale() {
        $language = '';
        $locale = $this->getLocale();
        if(!empty($locale)) {
            $language = \Locale::getPrimaryLanguage($locale);
        }
        return $language;
    }

    /**
     * @return string
     */
    private function getIpFromHeaders() {
        $ip = '';
        if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        elseif(!empty($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }

    /**
     * @return string
     */
    private function getUserAgentFromHeader() {
        $userAgent = '';
        if(!empty($_SERVER['HTTP_USER_AGENT']))
            $userAgent = $_SERVER['HTTP_USER_AGENT'];
        return $userAgent;
    }

    /**
     * @return string
     */
    protected function getActionCode() {
        return 'ui';
    }

    /**
     * @param array $params
     */
    public function setParams($params = []) {
        if(isset($params['country']))
            $this->setCountry($params['country']);

        if(isset($params['language']))
            $this->setLanguage($params['language']);

        if(isset($params['ip']))
            $this->setIp($params['ip']);

        if(isset($params['carrier']))
            $this->setCarrier($params['carrier']);

        if(isset($params['isRooted']))
            $this->setIsRooted($params['isRooted']);

        if(isset($params['userAgent']))
            $this->setUserAgent($params['userAgent']);
    }

    /**
     * @return bool
     */
    protected function validateParams() {

        if(empty($this->getCountry()))
            ApiClient::appendToErrors("User country is empty");

        if(empty($this->getLanguage()))
            ApiClient::appendToErrors("User language is empty");

        if(empty($this->getIp()))
            ApiClient::appendToErrors("User ip is empty");

        if(empty($this->getCarrier()))
            ApiClient::appendToErrors("User carrier is empty");

        if(empty($this->getUserAgent()))
            ApiClient::appendToErrors("User agent is empty");

        return true;
    }

    /**
     * @return void
     */
    protected function buildRequestData() {
        $mainUserId = $this->getMainUserId();
        $actionCode = $this->getActionCode();
        $actionData = [];

        $actionData['isRooted'] = $this->getIsRooted();

        $country = $this->getCountry();
        if(empty($country))
            $country = $this->getCountryFromLocale();
        $actionData['country'] = $country;

        $language = $this->getLanguage();
        if(empty($language))
            $language = $this->getLanguageFromLocale();
        $actionData['language'] = $language;

        $ip = $this->getIp();
        if(empty($ip))
            $ip = $this->getIpFromHeaders();
        $actionData['ip'] = $ip;

        $carrier = $this->getCarrier();
        if(!empty($carrier))
            $actionData['carrier'] = $carrier;

        $userAgent = $this->getUserAgent();
        if(empty($userAgent))
            $userAgent = $this->getUserAgentFromHeader();
        $actionData['userAgent'] = $userAgent;

        $this->requestData = [
            $mainUserId => [
                $actionCode => [$actionData]
            ]
        ];
    }
}