<?php

namespace LWS\Palu\Relay;

class RelaySwitch
{
    /**
     * @var int
     */
    private $index;

    /**
     * @var string
     */
    private $name;

    /**
     * @var TimeTable
     */
    private $timeTable;

    /**
     * @var bool
     */
    private $checkSensor = false;

    /**
     * @var bool
     */
    private $reversed = false;

    /**
     * @var int
     */
    private $sensorId;

    public function __construct($index, $name, $reversed, TimeTable $timeTable)
    {
        $this->index = (int)$index;
        $this->name = (string)$name;
        $this->reversed = (bool)$reversed;
        $this->timeTable = $timeTable;
    }

    public function turnOn()
    {
        if ($this->reversed === true) {
            exec("gpio write " . $this->index . " 0");
        } else {
            exec("gpio write " . $this->index . " 1");
        }
    }

    public function turnOff()
    {
        if ($this->reversed === true) {
            exec("gpio write " . $this->index . " 1");
        } else {
            exec("gpio write " . $this->index . " 0");
        }
    }

    /**
     * @return mixed
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return boolean
     */
    public function isOn()
    {
        if ($this->reversed === true) {
            return (exec("gpio read " . $this->index) == 0);
        } else {
            return (exec("gpio read " . $this->index) == 1);
        }
    }

    /**
     * @return TimeTable
     */
    public function getTimeTable()
    {
        return $this->timeTable;
    }

    /**
     * @return boolean
     */
    public function isCheckSensor()
    {
        return $this->checkSensor;
    }

    /**
     * @param boolean $value
     */
    public function setCheckSensor($value)
    {
        $this->checkSensor = (bool)$value;
    }

    /**
     * @return boolean
     */
    public function isReversed()
    {
        return $this->reversed;
    }

    /**
     * @return int
     */
    public function getSensorId()
    {
        return $this->sensorId;
    }

    /**
     * @param int $value
     */
    public function setSensorId($value)
    {
        $this->sensorId = (int)$value;
    }
}