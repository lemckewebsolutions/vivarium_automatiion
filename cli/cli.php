<?php
use LWS\Palu\Temperature\MaxLandTemperatureSchema;
use LWS\Palu\Temperature\MaxWaterTemperatureSchema;
use PHPushbullet\PHPushbullet;

chdir(__DIR__);
if (!$loader = include '../vendor/autoload.php') {
    die('Autoload file not found');
}

date_default_timezone_set("Europe/Amsterdam");

$app = new \Cilex\Application('Cilex');
$app->register(new \Cilex\Provider\ConfigServiceProvider(), array('config.path' => "config.json"));
$app->register(new Cilex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'   => 'pdo_mysql',
        'dbname'   => 'paludarium',
        'password' => '3DVgLAtWjqMGm6Jf'
    ),
));
$app->command(new \LWS\Palu\Command\SwitchRelay());
$app->command(new \LWS\Palu\Command\ReadSensors());
$app->command(new \LWS\Palu\Command\CleanDatabase());
$app->command(new \LWS\Palu\Command\LedOn());
$app->command(new \LWS\Palu\Command\LedOff());
$app->command(new \LWS\Palu\Command\NightOn());

$app["pushBullet"] = new PHPushbullet("YOURTOKENHERE");
$app["MaxWaterTemperatureSchema"] = new MaxWaterTemperatureSchema();
$app["MaxLandTemperatureSchema"] = new MaxLandTemperatureSchema();

$app->run();