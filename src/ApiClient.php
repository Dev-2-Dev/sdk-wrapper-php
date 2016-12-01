<?php

namespace Devtodev\StatApi;

use Devtodev\StatApi\ApiActions\CustomEventAction;
use Devtodev\StatApi\ApiActions\UserInfoAction;

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
     */
    public static function detailUserInfo($params = []){

    }

    public static function realPayment() {}
}