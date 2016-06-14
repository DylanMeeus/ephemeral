<?php

require_once "php/interfaces/IAjaxResponse.php";

class JsonResponse implements IAjaxResponse{

    public function generateResponse($success, $messages, $data){

        if(!is_bool($success)){
            return false;
        }

        $response = array(

            "success" => $success,

            "messages" => $messages,

            "data" => $data

        );

        return json_encode($response);

    }

}