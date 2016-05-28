<?php


class DebugHelper{

    // set to false to disable debugging.
    public static $development = true;

    public static function log($content)
    {
        if(DebugHelper::$development)
        {
            echo '<script>console.log("'.$content.'");</script>';
        }
    }
}

?>