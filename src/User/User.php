<?php

namespace LWS\Palu\User;

class User
{
    /**
     * @var string
     */
    private $authToken;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $fullName;

    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $userId;

    /**
     * User constructor.
     * @param int $userId
     * @param string $name
     * @param string $fullName
     * @param string $email
     * @param string $authToken
     */
    public function __construct($userId, $name, $fullName, $email, $authToken = "")
    {
        $this->userId = (int)$userId;
        $this->name = (string)$name;
        $this->fullName = (string)$fullName;
        $this->email = (string)$email;
        $this->authToken = (string)$authToken;
    }

    /**
     * @return string
     */
    public function getAuthToken()
    {
        return $this->authToken;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $value
     */
    public function setEmail($value)
    {
        $this->email = $value;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * @param string $value
     */
    public function setFullName($value)
    {
        $this->fullName = $value;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $value
     */
    public function setName($value)
    {
        $this->name = $value;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param int $value
     */
    public function setUserId($value)
    {
        $this->userId = $value;
    }
}