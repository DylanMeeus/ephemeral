<?php

require_once "php/interfaces/IAjaxResponse.php";

class JsonResponse implements IAjaxResponse{

    public function generateResponse($success, $messages, $data){

        if(!is_bool($success)){
            return false;
        }

        //$messages = htmlspecialchars($messages);
        //$data = htmlspecialchars($data);
        // TODO: Make these safe as they bypass the profile page's default htmlspecialchars outputs (Above commented lines aren't working)

        $response = array(

            "success" => $success,

            "messages" => $messages,

            "data" => $data

        );

        return json_encode($response);

    }

}