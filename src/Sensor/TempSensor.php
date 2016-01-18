<?php

namespace LWS\Palu\Sensor;

class TempSensor
{
    const WATER_SENSOR_ID = 0;

    /**
     * @var string
     */
    private $name;

    /**
     * @var float
     */
    private $value;

    /**
     * TempSensor constructor.
     * @param string $name
     * @param float $value
     */
    public function __construct($name, $value)
    {
        $this->name = (string)$name;
        $this->value = (float)$value;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return float
     */
    public function getValue()
    {
        return $this->value;
    }
}