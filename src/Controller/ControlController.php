<?php

namespace LWS\Palu\Controller;

use Silex\Application;
use Symfony\Component\Filesystem\LockHandler;
use Symfony\Component\HttpFoundation\Request;

class ControlController extends BaseController
{
    public function post(Request $request, Application $app, $switchId)
    {
        if ($this->getUser($app) === null) {
            return "Not authenticated";
        }

        if (isset($_POST["name"], $_POST["value"]) === false) {
            return "Incomplete data";
        }

        $switchIndex = (int)$switchId;

        switch ($_POST["name"]) {
            case "relay":
                return $this->controlRelay($switchIndex);
            case "autoPilot":
                return $this->controlAutoPilot($app, $switchIndex);
            default:
                return "Unknown control unit";
        }
    }

    private function controlRelay($switchIndex)
    {
        $result = exec("gpio write " . $switchIndex . " " . (int)$_POST["value"]);

        if ($result === "") {
            return "OK";
        }

        return "Er is iets fout gegaan";
    }

    private function controlAutoPilot(Application $app, $switchIndex)
    {
        $query = "
            update switch
            set
              autopilot = ?
            where
              switchid = ?
        ";

        $recordCount = $app['db']->executeUpdate($query, [
            ($_POST["value"] == 1 ? 'Y' : 'N'),
            $switchIndex
        ]);

        if ($recordCount == 1) {
            return "OK";
        }

        return "Er is iets fout gegaan";
    }
}