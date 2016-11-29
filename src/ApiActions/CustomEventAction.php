<?php
namespace Devtodev\StatApi\ApiAction;
use Devtodev\StatApi\ApiException;
use Devtodev\StatApi\StatApi;

final class CustomEventAction extends BaseApiAction {
    const MAX_LENGTH_EVENT_NAME = 72;
    const MAX_LENGTH_PARAM_NAME = 32;
    const MAX_COUNT_PARAM = 10;
    const TYPE_STRING = 'string';
    const TYPE_DOUBLE = 'double';
    protected $eventName = '';

    public function getEventName() {
        return $this->eventName;
    }

    public function setEventName($eventName = '') {
        $maxLength = self::MAX_LENGTH_EVENT_NAME;
        if(mb_strlen($eventName) <= $maxLength) {
            $this->eventName = $eventName;
        }
        else {
            throw new ApiException("Parameter 'eventName' is too large. Maximum length of {$maxLength} characters");
        }
    }

    protected function getActionCode() {
        return 'ce';
    }

    private function getParamAvailableTypes() {
        return [
            self::TYPE_STRING,
            self::TYPE_DOUBLE
        ];
    }

    protected function validateParams() {
        $this->validateParams = [];
        if(empty($this->eventName))
            throw new ApiException("Parameter 'eventName' is missing.");

        foreach($this->params as $eventItem) {
            if(is_array($eventItem)) {
                $name = (isset($eventItem['name'])) ? $eventItem['name'] : false;
                $type = (isset($eventItem['type'])) ? $eventItem['type'] : false;
                $value = (isset($eventItem['value'])) ? $eventItem['value'] : false;

                if(empty($name) || empty($type) || empty($value)) {
                    StatApi::appendToErrors("One of several required parameters missing");
                    continue;
                }

                $maxParamNameLength = self::MAX_LENGTH_PARAM_NAME;
                if(mb_strlen($name) > $maxParamNameLength) {
                    StatApi::appendToErrors("Parameter 'name' is too large. Maximum length of {$maxParamNameLength} characters");
                    continue;
                }

                if(!in_array($type, $this->getParamAvailableTypes())) {
                    StatApi::appendToErrors("Parameter with type '{$type}' is not supported.");
                    continue;
                }

                $this->validateParams[] = $eventItem;
            }
        }

        return !empty($this->validateParams);
    }

    protected function buildRequestData() {
        $mainUserId = $this->getMainUserId();
        $actionCode = $this->getActionCode();

        $i = 0;
        $dataEvents = [];

        $maxCountParam = self::MAX_COUNT_PARAM;
        foreach($this->validateParams as $eventItem) {
            if($i >= $maxCountParam) {
                StatApi::appendToErrors("Max count event parameters is {$maxCountParam}");
                break;
            }
            $name = $eventItem['name'];
            $type = $eventItem['type'];
            $value = $eventItem['value'];

            $dataEvents[$type][$name] = $value;
            $i++;
        }

        $actionData = [
            'name' => $this->getEventName(),
            'entries' => [
                [
                    't1' => $this->getTime(),
                    'level' => 1,
                    'p' => [
                        't1' => $dataEvents
                    ]
                ]
            ]
        ];

        $this->requestData = [
            $mainUserId => [
                $actionCode => [$actionData]
            ]
        ];
    }
}