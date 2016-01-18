<?php
use Silex\Application;

date_default_timezone_set("Europe/Amsterdam");

// Create the application to run.
$app = new Application();
$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Igorw\Silex\ConfigServiceProvider(__DIR__."/../config.json"));
$app->register(new Igorw\Silex\ConfigServiceProvider(__DIR__."/../config.local.json"));
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'   => 'pdo_mysql',
        'dbname'   => $app["database"]["database"],
        'password' => $app["database"]["password"]
    ),
));
$app["sensorRepository"] = function () use ($app) {
    return new \LWS\Palu\Repository\SensorRepository($app);
};
$app["relaySwitchRepository"] = function () use ($app) {
    return new \LWS\Palu\Repository\RelaySwitchRepository($app);
};
$app["userService"] = function () use ($app) {
    return new \LWS\Palu\Service\UserService($app);
};

$app->get("/", "LWS\\Palu\\Controller\\IndexController::get");

$app->get("/inloggen", "LWS\\Palu\\Controller\\LoginController::get");
$app->post("/inloggen", "LWS\\Palu\\Controller\\LoginController::login");

$app->get("/switch/{switchId}", "LWS\\Palu\\Controller\\SwitchController::get");

$app->post("/control/{switchId}", "LWS\\Palu\\Controller\\ControlController::post");

$app->get("/uitloggen", function (Silex\Application $app) {
    $app['session']->remove("user");
    setcookie("authToken", "", time()-3600);
    return $app->redirect("/");
});

return $app;