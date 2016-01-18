<?php

namespace LWS\Palu\Repository;

use LWS\Palu\Sensor\SwitchSensor;
use LWS\Palu\Sensor\TempSensor;
use Silex\Application;

class SensorRepository
{
    /**
     * @var Application
     */
    private $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function retrieveLastValueForSensor($sensorId)
    {
        $query = "
            select
              s.name,
              (
                select
                  sv.value
                from
                  sensorvalue sv
                where
                  sv.sensorid = s.sensorid
                order by
                  sv.timestamp DESC
                limit 1
              ) as value,
              s.type
            from
              sensor s
            where
              s.sensorid = ?
        ";

        $result = $this->app['db']->executeQuery($query, [$sensorId]);
        $sensor = null;

        while ($record = $result->fetch()) {
            switch ($record['type']) {
                case "temp":
                    $sensor = new TempSensor($record['name'], $record['value']);
                    break;
                case "switch":
                    $sensor = new SwitchSensor($record['name'], (bool)$record['value']);
                    break;
            }
        }

        return $sensor;
    }

    public function retrieveTempValuesForLastWeek()
    {
        $query = "
            select
                DATE_FORMAT(sv.timestamp, '%Y-%m-%d %H:%i') as timestamp,
                sv.value as waterValue,
                (select
                   sv2.value
                 from
                   sensorvalue sv2
                 where
                   sv2.sensorid = 1 and
                   sv2.timestamp = sv.timestamp) as landValue,
                (select
                   sv3.value
                 from
                   sensorvalue sv3
                 where
                   sv3.sensorid = 2 and
                   sv3.timestamp = sv.timestamp) as warmteSpotValue
            from
                sensorvalue sv
            where
                sv.timestamp > date_sub(now(), INTERVAL 7 day) and
                sv.sensorid = 0
            order by
                sv.timestamp ASC
        ";

        $result = $this->app["db"]->executeQuery($query);
        $values = [];

        while ($record = $result->fetch()) {

            if ($record['waterValue'] > 0 && $record['landValue'] > 0 && $record['warmteSpotValue'] > 0) {
                $values[] = [
                    "timestamp" => $record['timestamp'],
                    "waterValue" => $record['waterValue'],
                    "landValue" => $record['landValue'],
                    "warmteSpotValue" => $record['warmteSpotValue']
                ];
            }
        }

        return $values;
    }

    /**
     * @return TempSensor[]
     */
    public function retrieveLatestTempValues()
    {
        $query = "
            select
              s.name,
              (
                select
                  sv.value
                from
                  sensorvalue sv
                where
                  sv.sensorid = s.sensorid
                order by
                  sv.timestamp DESC
                limit 1
              ) as value
            from
              sensor s
            where
              s.type = ?
        ";

        $result = $this->app['db']->executeQuery($query, ['temp']);
        $sensors = [];

        while ($record = $result->fetch()) {
            $sensors[] = new TempSensor($record['name'], $record['value']);
        }

        return $sensors;
    }
}