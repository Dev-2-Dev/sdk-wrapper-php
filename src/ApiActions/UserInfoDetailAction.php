<?php

namespace Devtodev\StatApi\ApiActions;

use Devtodev\StatApi\ApiClient;

/**
 * Class UserInfoDetailAction
 *
 * @package Devtodev\StatApi\ApiActions
 */
final class UserInfoDetailAction extends BaseApiAction {
    /**
     * @var int
     */
    protected $age = 0;
    /**
     * @var bool
     */
    protected $cheater = false;
    /**
     * @var bool
     */
    protected $tester = false;
    /**
     * @var int
     */
    protected $gender = 0;
    /**
     * @var string
     */
    protected $name = '';
    /**
     * @var string
     */
    protected $email = '';
    /**
     * @var string
     */
    protected $phone = '';
    /**
     * @var string
     */
    protected $photo = '';
    /**
     * @var array
     */
    protected $customProperties = [];


    /**
     * @return int
     */
    public function getAge() {
        return $this->age;
    }

    /**
     * @param int $age
     */
    public function setAge($age) {
        $this->age = (int) $age;
    }

    /**
     * @return boolean
     */
    public function isCheater() {
        return $this->cheater;
    }

    /**
     * @param boolean $cheater
     */
    public function setCheater($cheater) {
        $this->cheater = (bool) $cheater;
    }

    /**
     * @return boolean
     */
    public function isTester() {
        return $this->tester;
    }

    /**
     * @param boolean $tester
     */
    public function setTester($tester) {
        $this->tester = (bool) $tester;
    }

    /**
     * @return int
     */
    public function getGender() {
        return $this->gender;
    }

    /**
     * @param int $gender
     */
    public function setGender($gender) {
        $this->gender = (int) $gender;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name) {
        $this->name = (string) $name;
    }

    /**
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email) {
        $this->email = (string) $email;
    }

    /**
     * @return string
     */
    public function getPhone() {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone) {
        $this->phone = (string) $phone;
    }

    /**
     * @return string
     */
    public function getPhoto() {
        return $this->photo;
    }

    /**
     * @param string $photo
     */
    public function setPhoto($photo) {
        $this->photo = (string) $photo;
    }

    /**
     * @return array
     */
    public function getCustomProperties() {
        return $this->customProperties;
    }

    /**
     * @param string $propertyKey
     * @param null $propertyValue
     *
     * @internal param array $customProperties
     */
    public function addCustomProperty($propertyKey = '', $propertyValue = null) {
        if(!empty($propertyKey) && !empty($propertyValue)){
            if(is_string($propertyKey) && (is_string($propertyValue) || is_numeric($propertyValue)) || is_array($propertyValue) || is_null($propertyValue)) {
                $this->customProperties[$propertyKey] = $propertyValue;
            } else {
                ApiClient::appendToErrors("User custom property. Type of property value is not supported.");
            }
        } else {
            ApiClient::appendToErrors("User custom property. Empty key.");
        }
    }

    /**
     * @return string
     */
    protected function getActionCode() {
        return 'pl';
    }

    /**
     * @return bool
     */
    protected function validateParams() {
        if (empty($this->getAge()))
            ApiClient::appendToErrors("User age is empty");

        if (empty($this->getAge()))
            ApiClient::appendToErrors("User cheater is empty");

        if ($this->getGender() < 0 || $this->getGender() > 2)
            ApiClient::appendToErrors("User gender wrong set");

        if (empty($this->getName()))
            ApiClient::appendToErrors("User name is empty");

        if (empty($this->getEmail()))
            ApiClient::appendToErrors("User email is empty");

        if (empty($this->getPhone()))
            ApiClient::appendToErrors("User phone is empty");

        if (empty($this->getPhoto()))
            ApiClient::appendToErrors("User photo is empty");

        return true;
    }

    /**
     * @return void
     */
    protected function buildRequestData() {
        $mainUserId = $this->getMainUserId();
        $actionCode = $this->getActionCode();

        $actionData = [
            'data' => [
                'age' => $this->getAge(),
                'cheater' => $this->isCheater(),
                'tester' => $this->isTester(),
                'gender' => $this->getGender(),
                'name' => $this->getName(),
                'email' => $this->getEmail(),
                'phone' => $this->getPhone(),
                'photo' => $this->getPhoto()
            ],
            'tuimestamp' => $this->getTime()
        ];

        if (!empty($this->customProperties))
            array_merge($actionData['data'], $this->getCustomProperties());

        $this->requestData = [
            $mainUserId => [
                $actionCode => [$actionData]
            ]
        ];
    }

    /**
     * @param array $params
     */
    public function setParams($params = []) {
        if(is_array($params)) {
            foreach($params as $param => $value) {
                $methodName = "set{$param}";
                if(method_exists($this, $methodName)) {
                    $this->$methodName($value);
                } else {
                    $this->addCustomProperty($param, $value);
                }
            }
        }
    }
}