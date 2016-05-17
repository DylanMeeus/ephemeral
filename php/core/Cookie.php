<?php

if(!defined("SERVLET"))
    die("You may not view this page.");

class Cookie{

    private $cookieID, $userID, $cookieTypeID, $value;

    /**
     * @return mixed
     */
    public function getCookieID()
    {
        return $this->cookieID;
    }

    /**
     * @param mixed $cookieID
     */
    public function setCookieID($cookieID)
    {
        $this->cookieID = $cookieID;
    }

    /**
     * @return mixed
     */
    public function getUserID()
    {
        return $this->userID;
    }

    /**
     * @param mixed $userID
     */
    public function setUserID($userID)
    {
        $this->userID = $userID;
    }

    /**
     * @return mixed
     */
    public function getCookieTypeID()
    {
        return $this->cookieTypeID;
    }

    /**
     * @param mixed $cookieTypeID
     */
    public function setCookieTypeID($cookieTypeID)
    {
        $this->cookieTypeID = $cookieTypeID;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
}