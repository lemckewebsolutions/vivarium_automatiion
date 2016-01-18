<?php

namespace LWS\Palu\Relay;

class TimeTable
{
    /**
     * @var bool
     */
    private $alwaysOn = false;

    /**
     * @var bool
     */
    private $autopilot = false;

    /**
     * @var TimeTableEntry[]
     */
    private $timeTableEntries = [];

    /**
     * TimeTable constructor.
     * @param bool $alwaysOn
     * @param bool $autopilot
     * @param TimeTableEntry[] $timeTableEntries
     */
    public function __construct($alwaysOn, $autopilot, array $timeTableEntries)
    {
        $this->alwaysOn = (bool)$alwaysOn;
        $this->autopilot = (bool)$autopilot;
        $this->timeTableEntries = $timeTableEntries;
    }

    /**
     * @return boolean
     */
    public function isAlwaysOn()
    {
        return $this->alwaysOn;
    }

    /**
     * @return boolean
     */
    public function isAutoPilot()
    {
        return $this->autopilot;
    }

    /**
     * @return TimeTableEntry[]
     */
    public function getTimeTableEntries()
    {
        return $this->timeTableEntries;
    }
}