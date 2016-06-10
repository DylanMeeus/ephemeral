<?php

include 'php/config/slack.php';

/**
 * Class SlackData
 * Interacts with slack.
 */
class SlackData{

    private $slackUrl = SLACKURL;
    private $username = SLACKUSERNAME;

    public function postToSlack($message)
    {
        try{

            $curl = curl_init($this->slackUrl);

            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // TODO: REMOVE BEFORE PUBLISHING

            curl_setopt($curl, CURLOPT_URL, $this->slackUrl);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, "{\"text\" : \"" . $message . "\", \"username\" : \"Shoutbox-bot\"}");
            curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: text/plain"));

            $res = curl_exec($curl);

        }catch(Exception $ex){

            DebugHelper::log("error in slack message: " . $ex->getMessage());
        }
    }
}


?>