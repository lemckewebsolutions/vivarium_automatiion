<?php

namespace LWS\Palu\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class SwitchController extends BaseController
{
    public function get(Request $request, Application $app, $switchId)
    {
        $this->load($app);

        $this->assignTemplateVariables("switch", $app["relaySwitchRepository"]->retrieveSwitch($switchId));

        return $this->getTwig()->render("switch.html", $this->getTemplateVariables());
    }
}