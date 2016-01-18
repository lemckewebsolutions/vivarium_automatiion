<?php
namespace LWS\Palu\Controller;

use LWS\Framework\Notifications\Notification;
use LWS\Palu\Repository\RelaySwitchRepository;
use LWS\Palu\Service\UserService;
use LWS\Palu\User\User;
use Silex\Application;

abstract class BaseController
{
    private $templateVariables = [];

    private $twig;

    public function __construct()
    {
        $loader = new \Twig_Loader_Filesystem('templates');
        $twig = new \Twig_Environment($loader, ['debug' => true]);
        $twig->addExtension(new \Twig_Extension_Debug());

        $this->twig = $twig;

        $this->assignTemplateVariables("refresh", false);
    }

    protected function assignTemplateVariables($key, $value)
    {
        if ($this->templateVariables === null) {
            $this->templateVariables = [];
        }

        $this->templateVariables[$key] = $value;

        return $this->templateVariables;
    }

    protected function load(Application $app) {
        $this->assignTemplateVariables("user", $this->getUser($app));
        $this->assignTemplateVariables("relaySwitches", $this->getRelaySwitches($app));
    }

    private function getRelaySwitches(Application $app)
    {
        $repo = new RelaySwitchRepository($app);

        return $repo->retrieveSwitches();
    }

    /**
     * @param Application $app
     * @return bool
     */
    protected function loggedIn(Application $app)
    {
        return $this->getUser($app) !== null;
    }

    /**
     * @return Notification[]
     */
    protected function getNotifications()
    {
        if (isset($_SESSION["notifications"]) === true &&
            is_array($_SESSION["notifications"]) === true) {
            $notifications = [];

            while ($notification = array_shift($_SESSION["notifications"])) {
                $notifications[] = $notification;
            }

            return $notifications;
        }

        return [];
    }

    /**
     * @param Notification $notification
     */
    protected function addNotification(Notification $notification)
    {
        $_SESSION["notifications"][] = $notification;
    }

    /**
     * @return array
     */
    protected function getTemplateVariables()
    {
        return $this->templateVariables;
    }

    /**
     * @return \Twig_Environment
     */
    protected function getTwig()
    {
        return $this->twig;
    }

    /**
     * @param Application $app
     * @return User|null
     */
    protected function getUser(Application $app)
    {
        if ($app['session']->get("user") === null &&
            isset($_COOKIE["authToken"]) === true) {

            /* @var UserService $userService*/
            $userService = $app["userService"];
            $user = $userService->getUserByAuthToken($_COOKIE["authToken"]);

            if ($user !== null) {
                $app['session']->set("user", $user);

                return $user;
            }
        }

        return $app['session']->get("user");
    }
}