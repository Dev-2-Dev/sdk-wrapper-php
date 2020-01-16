<?php

namespace Devtodev\StatApi\ApiActions;

use Devtodev\StatApi\ApiClient;

/**
 * Class RealPaymentAction
 *
 * @package Devtodev\StatApi\ApiActions
 */
final class RealPaymentAction extends BaseApiAction {
    /**
     * @var string
     */
    protected $transactionId = '';

    /**
     * @var float
     */
    protected $productPrice = 0;

    /**
     * @var string
     */
    protected $productName = '';

    /**
     * @var string
     */
    protected $transactionCurrencyISOCode = '';

    /**
     * @var int
     */
    protected $level = 1;

    /**
     * @return string
     */
    public function getTransactionId() {
        return $this->transactionId;
    }

    /**
     * @param string $transactionId
     */
    public function setTransactionId($transactionId) {
        $this->transactionId = $transactionId;
    }

    /**
     * @return int
     */
    public function getProductPrice() {
        return $this->productPrice;
    }

    /**
     * @param float $productPrice
     */
    public function setProductPrice($productPrice) {
        $this->productPrice = (float) $productPrice;
    }

    /**
     * @return string
     */
    public function getProductName() {
        return $this->productName;
    }

    /**
     * @param string $productName
     */
    public function setProductName($productName) {
        $this->productName = $productName;
    }

    /**
     * @return string
     */
    public function getTransactionCurrencyISOCode() {
        return $this->transactionCurrencyISOCode;
    }

    /**
     * @param string $transactionCurrencyISOCode
     */
    public function setTransactionCurrencyISOCode($transactionCurrencyISOCode) {
        $this->transactionCurrencyISOCode = $transactionCurrencyISOCode;
    }

    /**
     * @return int
     */
    public function getLevel() {
        return $this->level;
    }

    /**
     * @param int $level
     */
    public function setLevel($level) {
        $this->level = (int) $level;
    }

    /**
     * @return string
     */
    protected function getActionCode() {
        return 'rp';
    }

    /**
     * @return bool
     * @throws ApiException
     */
    protected function validateParams() {
        $isValidate = true;

        if(empty($this->getTransactionId())){
            ApiClient::appendToErrors("Payment transactionId is empty");
            $isValidate = false;
        }

        if(empty($this->getProductName())){
            ApiClient::appendToErrors("Payment productName is empty");
            $isValidate = false;
        }

        if(empty($this->getTransactionCurrencyISOCode())){
            ApiClient::appendToErrors("Payment transactionCurrencyISOCode is empty");
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
        $actionData = [];

        $actionData['name'] = $this->getProductName();
        $actionData['entries'] = [[
            'orderId' => $this->getTransactionId(),
            'price' => $this->getProductPrice(),
            'currencyCode' => $this->getTransactionCurrencyISOCode(),
            'timestamp' => $this->getTime(),
            'level' => $this->getLevel(),
        ]];
        $this->requestData = [
            $mainUserId => [
                $actionCode => [$actionData]
            ]
        ];
    }
}