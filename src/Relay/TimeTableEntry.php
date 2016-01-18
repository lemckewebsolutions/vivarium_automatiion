<?php

namespace LWS\Palu\Relay;

class TimeTableEntry
{
    private $from;

    private $to;

    /**
     * TimeTableEntry constructor.
     * @param (string)$from
     * @param (string)$to
     */
    public function __construct($from, $to)
    {
        $this->from = (string)$from;
        $this->to = (string)$to;
    }

    /**
     * @return string
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @return string
     */
    public function getTo()
    {
        return $this->to;
    }
}