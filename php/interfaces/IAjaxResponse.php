<?php

interface IAjaxResponse{

    public function generateResponse($success, $messages, $data);

}