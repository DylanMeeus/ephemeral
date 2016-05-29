<?php


/**
 * Class SlackData
 * Interacts with slack.
 */
class SlackData
{


    private $slackurl = "https://hooks.slack.com/services/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"; //TODO: put this in a safer location
    private $username = "slackbot";

    public function postToSlack($message)
    {

        $curl = curl_init($this->slackurl);

        curl_setopt($curl, CURLOPT_URL, $this->slackurl);
        curl_setopt($curl,CURLOPT_POST, 1);
        curl_setopt($curl,CURLOPT_POSTFIELDS, "{\"text\" : \"" . $message . "\", \"username\" : \"Shoutbox-bot\"}");
        curl_setopt($curl,CURLOPT_HTTPHEADER, array("Content-Type: text/plain"));

        $res = curl_exec($curl);
        DebugHelper::log("posted to slack");
    }
}


?>