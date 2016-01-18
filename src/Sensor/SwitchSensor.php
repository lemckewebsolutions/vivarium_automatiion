<?php

namespace LWS\Palu\Sensor;

class SwitchSensor
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var float
     */
    private $value;

    /**
     * @param string $name
     * @param bool $value
     */
    public function __construct($name, $value)
    {
        $this->name = (string)$name;
        $this->value = (bool)$value;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function getValue()
    {
        return $this->value;
    }
}