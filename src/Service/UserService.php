<?php

namespace LWS\Palu\Service;

use LWS\Palu\User\User;
use Silex\Application;

class UserService
{
    /**
     * @var Application
     */
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @param string $username
     * @param string $password
     * @return User|null
     */
    public function tryLogin($username, $password)
    {
        $salt = $this->app["salt"];
        $saltedPass = md5($salt.md5($this->app->escape($password)));
        $username = $this->app->escape($username);

        $query = "select
                    u.userid,
                    u.name,
                    u.fullname,
                    u.email,
                    u.authtoken
                  from
                     user u
                  WHERE
                     (
                       lower(u.name) = ? or
                       lower(u.fullname) = ? or
                       lower(u.email) = ?
                     ) and
                     u.password = ?";

        $result = $this->app['db']->fetchAssoc($query, [$username, $username, $username, $saltedPass]);

        if ($result !== false) {
            return new User(
                $result['userid'],
                $result['name'],
                $result['fullname'],
                $result['email'],
                $result['authtoken']
            );
        }

        return null;
    }

    /**
     * @param string $authToken
     * @return User|null
     */
    public function getUserByAuthToken($authToken)
    {
        $authToken = $this->app->escape($authToken);

        $query = "select
                    u.userid,
                    u.name,
                    u.fullname,
                    u.email,
                    u.authtoken
                  from
                     user u
                  WHERE
                     u.authtoken = ?";

        $result = $this->app['db']->fetchAssoc($query, [$authToken]);

        if ($result !== false) {
            return new User(
                $result['userid'],
                $result['name'],
                $result['fullname'],
                $result['email'],
                $result['authtoken']
            );
        }

        return null;
    }
}