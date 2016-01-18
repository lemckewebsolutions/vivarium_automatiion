<?php

namespace LWS\Palu\Controller;

use LWS\Framework\Notifications\Notification;
use LWS\Palu\Service\UserService;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class LoginController extends BaseController
{
    public function get()
    {
        return $this->getTwig()->render("login.html", ["notifications" => $this->getNotifications()]);
    }

    public function login(Request $request, Application $app)
    {
        if (isset($_POST["username"], $_POST["password"]) === false) {
            return $this->get();
        }

        /* @var UserService $userService*/
        $userService = $app["userService"];
        $user = $userService->tryLogin($_POST["username"], $_POST["password"]);

        if ($user == null) {
            $this->addNotification(new Notification(
                "Inloggen mislukt. Gebruikersnaam of wachtwoord incorrect.",
                Notification::LEVEL_ERROR
            ));

            return $this->get();
        }

        $request->getSession()->set("user", $user);

        if (isset($_POST['remember']) === true && $_POST["remember"] == "Y") {
            setcookie("authToken", $user->getAuthToken(), time()+60*60*24*30);
        }

        $this->addNotification(new Notification("Successvol ingelogd.", Notification::LEVEL_SUCCESS));

        return $app->redirect("/");
    }
}