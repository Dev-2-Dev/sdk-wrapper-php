<?php

namespace Devtodev\StatApi;

use Devtodev\StatApi\ApiActions\CustomEventAction;
use Devtodev\StatApi\ApiActions\TrackingAvailabilityAction;
use Devtodev\StatApi\ApiActions\UserInfoAction;
use Devtodev\StatApi\ApiActions\UserInfoDetailAction;
use Devtodev\StatApi\ApiActions\RealPaymentAction;

/**
 * Class ApiClient
 *
 * @package Devtodev\StatApi
 */
final class ApiClient {
    private static $errors = [];

    /**
     * @return array
     */
    public static function getErrors() {
        return self::$errors;
    }

    /**
     * @param string $errorText
     */
    public static function appendToErrors($errorText = '') {
        self::$errors[] = $errorText;
    }

    /**
     * Init params
     *
     * @param array $params
     * @param string params.api_key - devtodev API key
     * @param string params.main_user_id - devtodev main user id
     */
    public static function init($params = []) {
        $configInstance = Config::getInstance();
        $configInstance->setParams($params);
    }

    /**
     * Tracks custom events.
     *
     * @param string $eventName - event name (max. 72 symbols)
     * @param array $eventParams - array of array event parameters. Up to 10 params.
     * @param [Array[]] params - array of event parameters. Up to 10 params.
     * @param string params[].name - parameter name (max. 32 symbols)
     * @param string params[].type - parameter value type. Can be "double" or "string".
     * @param string|number params [].value - parameter value. (max. 255 symbols)
     * Event params example
     *  [
     *      [
     *          'name' => 'score',
     *          'type' => 'double',
     *          'value' => 100500
     *      ],
     *      [
     *          'name' => 'type',
     *          'type' => 'string',
     *          'value' => 'fatality'
     *      ]
     *  ]
     */
    public static function customEvent($eventName = '', $eventParams = []) {
        $customEventAction = new CustomEventAction();
        $customEventAction->setEventName($eventName);
        $customEventAction->setParams($eventParams);
        $customEventAction->run();
    }

    /**
     * Send user info
     *
     * @param array $userInfoParams
     * @param string userInfoParams.country - parameter country of a user (format ISO 3166-1 alpha-2)
     * @param string userInfoParams.language - parameter language of a user (format ISO 639-1 (1998)
     * @param string userInfoParams.ip - parameter user ip
     * @param string userInfoParams.carrier - parameter name of a network operator
     * @param int userInfoParams.isRooted - parameter rooted (jailbroken) device (1 - rooted)
     * @param string userInfoParams.userAgent - parameter browser user-agent
     */
    public static function userInfo($userInfoParams = []){
        $userInfoAction = new UserInfoAction();
        $userInfoAction->setParams($userInfoParams);
        $userInfoAction->run();
    }

    /**
     * Send detail user info
     * @param array $userInfoDetailParams
     * @param string userInfoDetailParams.age - user's age in years
     * @param string userInfoDetailParams.cheater - true, if a user is a cheater.
     * In case you have your own methods to detect cheaters, you can mark such users.
     * Event records made by cheaters will be ignored when counting statistical metrics.
     * @param string userInfoDetailParams.tester - true, if a user is a tester
     * Attention! This marker cannot be removed through the SDK (It can not be set to false after true).
     * Event records made by testers will be ignored when counting statistical metrics.
     * @param string userInfoDetailParams.gender - user's sex 0-unknown, 1-male, 2-female
     * @param int userInfoDetailParams.name - user's name
     * @param string userInfoDetailParams.email - user's e-mail
     * @param string userInfoDetailParams.phone - user's phone phone
     * @param string userInfoDetailParams.photo - user's photo
     *
     * Custom characteristics of a user in a key-value format
     * @param string|number|array|null userInfoDetailParams.youKey
     *  "key1" : "stringValue",                //String value
     *  "key2" : 1.54,                         //Number value
     *  "key3" : [1,2,"something"]             //Array
     */
    public static function userInfoDetail($userInfoDetailParams = []){
        $userInfoDetailAction= new UserInfoDetailAction();
        $userInfoDetailAction->setParams($userInfoDetailParams);
        $userInfoDetailAction->run();
    }

    /**
     * Register transactions made through the platform's payment system.
     * 
     * @param $transactionId string - transaction ID
     * @param $productPrice float - product price (in user's currency)
     * @param $productName string - product name
     * @param $transactionCurrencyISOCode string - transaction currency (ISO 4217 format)
     * @param $userLevel int - user level
     */
    public static function realPayment($transactionId = '', $productPrice = 0.0, $productName = '', $transactionCurrencyISOCode = '', $userLevel = 1) {
        $realPaymentAction = new RealPaymentAction();
        $realPaymentAction->setTransactionId($transactionId);
        $realPaymentAction->setProductPrice($productPrice);
        $realPaymentAction->setProductName($productName);
        $realPaymentAction->setTransactionCurrencyISOCode($transactionCurrencyISOCode);
        $realPaymentAction->setLevel($userLevel);
        $realPaymentAction->run();
    }

    /**
     * The method of limiting the processing of user data. The right to be forgotten.
     * use 'false' to erase user's personal data and stop collecting data of this user.
     * 'true' if you want to resume data collection.
     *
     * @param $isTrackingAllowed boolean - tracking available
     */
    public static function setTrackingAvailability($isTrackingAllowed) {
        $trackingAvailabilityAction = new TrackingAvailabilityAction();
        $trackingAvailabilityAction->setIsTrackingAllowed($isTrackingAllowed);
        $trackingAvailabilityAction->run();
    }
}