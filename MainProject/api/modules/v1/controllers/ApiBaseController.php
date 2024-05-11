<?php

namespace api\modules\v1\controllers;
use Yii;
use yii\rest\Controller;

class ApiBaseController extends Controller{

    public function response($code, $data=null){
            $message = $this->getStatusCodeMessage($code);
            $response = [
                'code'=>$code,
                'message'=>$message,
                'response' => $data,
            ];

        $this->setHeader($code);

        echo json_encode($response);
        die();
    }

    private function getStatusCodeMessage($status){
        $codes = [

            //Success Codes
            200 => 'OK',
            201 => 'New Resource Created',

            //Client Error
            401 => 'Unauthorised',
            404 => 'Resource Not Found',
            405 => 'Method Not allowed',
            409 => 'Conflict', //Validation Error or Creating resource that already exists
            422 => 'Missing Information in Request',

            //Server Error
            500 => 'Information cant be processes', //data didnt saved
            503 => 'Service Unavailable',
        ];
        return (isset($codes[$status])) ? $codes[$status] : '';
    }

    private function setHeader($status){
        $status_header = 'HTTP/2 ' . $status . ' ' . $this->getStatusCodeMessage($status);
        $content_type = "application/json; charset=utf-8";
        header($status_header);
        header('Content-type: ' . $content_type);
        header('X-Powered-By: ' . "Doctors Portal");
    }

}