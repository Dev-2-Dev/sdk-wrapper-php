<?php
namespace Devtodev\StatApi\ApiActions;
use Devtodev\StatApi\ApiClient;

/**
 * Class TrackingAvailabilityAction
 * @package Devtodev\StatApi\ApiActions
 */
class TrackingAvailabilityAction extends BaseApiAction {
    /**
     * @var boolean
     */
    protected $isTrackingAllowed = null;

    /**
     * @return boolean
     */
    public function getIsTrackingAllowed() {
        return $this->isTrackingAllowed;
    }

    /**
     * @param string $isTrackingAllowed
     */
    public function setIsTrackingAllowed($isTrackingAllowed) {
        $this->isTrackingAllowed = $isTrackingAllowed;
    }

    /**
     * @return string
     */
    protected function getActionCode() {
        return 'ts';
    }

    /**
     * @return boolean
     * @throws ApiException
     */
    protected function validateParams() {
        $isValidate = true;
        if($this->getIsTrackingAllowed() === null){
            ApiClient::appendToErrors("Tracking status is empty");
            $isValidate = false;
        }
        return $isValidate;
    }

    /**
     * @return void
     */
    protected function buildRequestData() {
        $mainUserId = $this->getMainUserId();
        $actionCode = $this->getActionCode();
        $actionData= [
            'isTrackingAllowed' => $this->getIsTrackingAllowed(),
            'timestamp' => $this->getTime()
        ];

        $this->requestData = [
            $mainUserId => [
                $actionCode => [$actionData]
            ]
        ];
    }
}