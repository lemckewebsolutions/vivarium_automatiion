<?php

namespace LWS\Palu\Repository;

use LWS\Palu\Relay\RelaySwitch;
use LWS\Palu\Relay\TimeTable;
use LWS\Palu\Relay\TimeTableEntry;
use Silex\Application;

class RelaySwitchRepository
{
    /**
     * @var Application
     */
    private $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * @param int $switchId
     * @return RelaySwitch
     */
    public function retrieveSwitch($switchId)
    {
        $query = "
            select
              s.switchid,
              s.name,
              s.alwayson,
              s.autopilot,
              s.checksensor,
              s.sensorid,
              s.reversed
            from
              switch s
            where
              s.switchid = ?
        ";

        $record = $this->app['db']->executeQuery($query, [$switchId])->fetch();

        $timeTable = new TimeTable(
            ($record['alwayson'] == 'Y'),
            ($record['autopilot'] == 'Y'),
            $this->retrieveTimeTable($record['switchid'])
        );

        $switch = new RelaySwitch(
            $record['switchid'],
            $record['name'],
            $record['reversed'] == 'Y',
            $timeTable
        );

        if ($record["checksensor"] == 'Y') {
            $switch->setCheckSensor(true);
            $switch->setSensorId($record['sensorid']);
        }

        return $switch;
    }

    /**
     * @return RelaySwitch[]
     */
    public function retrieveSwitches()
    {
        $query = "
            select
              s.switchid,
              s.name,
              s.alwayson,
              s.autopilot,
              s.checksensor,
              s.sensorid,
              s.reversed
            from
              switch s
            order by
              s.switchid
        ";

        $result = $this->app['db']->executeQuery($query);
        $relaySwitches = [];

        while ($record = $result->fetch()) {
            $timeTable = new TimeTable(
                ($record['alwayson'] == 'Y'),
                ($record['autopilot'] == 'Y'),
                $this->retrieveTimeTable($record['switchid'])
            );

            $switch = new RelaySwitch(
                $record['switchid'],
                $record['name'],
                $record['reversed'] == 'Y',
                $timeTable
            );

            if ($record["checksensor"] == 'Y') {
                $switch->setCheckSensor(true);
                $switch->setSensorId($record['sensorid']);
            }

            $relaySwitches[] = $switch;
        }

        return $relaySwitches;
    }

    /**
     * @param int $switchId
     * @return TimeTableEntry[]
     */
    private function retrieveTimeTable($switchId)
    {
        $query = "
            select
              e.from,
              e.to
            from
              switchtimetableentry e
            where
              e.switchid = ?
            order by
              e.from
        ";

        $result = $this->app['db']->executeQuery($query, [$switchId]);
        $entries = [];

        while ($record = $result->fetch()) {
            $entries[] = new TimeTableEntry($record['from'], $record['to']);
        }

        return $entries;
    }
}