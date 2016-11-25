<?php

if(!function_exists('curl_init'))
    throw new Exception('CURL PHP extension is required');

if(!function_exists('json_decode'))
    throw new Exception('JSON PHP extension is required');

require_once(dirname(__FILE__) . '/Devtodev/DevtodevConfig.php');
require_once(dirname(__FILE__) . '/Devtodev/DevtodevStatApi.php');
require_once(dirname(__FILE__) . '/Devtodev/DevtodevException.php');
require_once(dirname(__FILE__) . '/Devtodev/ApiResponse.php');
require_once(dirname(__FILE__) . '/Devtodev/ApiRequest.php');
require_once(dirname(__FILE__) . '/Devtodev/ApiActions/BaseApiAction.php');
require_once(dirname(__FILE__) . '/Devtodev/ApiActions/CustomEventAction.php');

