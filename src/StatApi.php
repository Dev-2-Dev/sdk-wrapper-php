<?php

namespace Devtodev\StatApi;

use Devtodev\StatApi\ApiAction\CustomEventAction;

final class StatApi {
    private static $errors = [];

    public static function getErrors() {
        return self::$errors;
    }

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

    public static function realPayment() {
    }
}