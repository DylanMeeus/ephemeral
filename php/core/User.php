<?php

if(!defined("SERVLET"))
    die("You may not view this page.");

class User{

    // TODO: implement a "toString" interface, to print it as json / xml?

    private
        $userID,
        $email,
        $firstName,
        $lastName,
        $signature,
        $personalMessage,
        $avatar,
        $fullAvatar,
        $roleID,
        $username;

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
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return mixed
     */
    public function getSignature()
    {
        return $this->signature;
    }

    /**
     * @param mixed $signature
     */
    public function setSignature($signature)
    {
        $this->signature = $signature;
    }

    /**
     * @return mixed
     */
    public function getPersonalMessage()
    {
        return $this->personalMessage;
    }

    /**
     * @param mixed $personalMessage
     */
    public function setPersonalMessage($personalMessage)
    {
        $this->personalMessage = $personalMessage;
    }

    /**
     * @return mixed
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param mixed $avatar
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
    }

    /**
     * @return mixed
     */
    public function getFullAvatar()
    {
        return $this->fullAvatar;
    }

    /**
     * @param mixed $fullAvatar
     */
    public function setFullAvatar($fullAvatar)
    {
        $this->fullAvatar = $fullAvatar;
    }

    /**
     * @return mixed
     */
    public function getRoleID()
    {
        return $this->roleID;
    }

    /**
     * @param mixed $roleID
     */
    public function setRoleID($roleID)
    {
        $this->roleID = $roleID;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }
}