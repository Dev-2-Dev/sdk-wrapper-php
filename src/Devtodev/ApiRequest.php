<?php

class ApiRequest{
    public static function send($data= []){
        echo json_encode($data);
    }
}