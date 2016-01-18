<?php

namespace LWS\Palu\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class IndexController extends BaseController
{
    public function get(Request $request, Application $app)
    {
        $this->load($app);
        $this->assignTemplateVariables("refresh", true);

        $this->assignTemplateVariables("tempValues", $app["sensorRepository"]->retrieveTempValuesForLastWeek());
        $this->assignTemplateVariables("tempSensors", $app["sensorRepository"]->retrieveLatestTempValues());

        return $this->getTwig()->render("index.html", $this->getTemplateVariables());
    }
}