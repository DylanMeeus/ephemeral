<?php


class DebugHelper{

    // could make this a bit more flexible, so it can print more than just strings?
    public static function log($content)
    {
        echo '<script>console.log("'.$content.'");</script>';
    }
}

?>