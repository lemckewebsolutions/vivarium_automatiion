<?php

namespace LWS\Palu\Command;

use Cilex\Command\Command;
use LWS\Palu\Sensor\SwitchSensor;
use LWS\Palu\Sensor\TempSensor;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ReadSensors extends Command
{
    public function configure()
    {
        $this
            ->setName('readSensors')
            ->setDescription('Read the sensors and put the value in the db');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sensors = $this->retrieveSensors();

        if (count(array_filter($sensors, function($sensor) {
            return $sensor instanceof TempSensor && $sensor->getValue() > 40;
        })) > 0) {
            // Misread, try again
            $sensors = $this->retrieveSensors();
        }



        foreach ($sensors as $key => $sensor) {
            $query = "
                  insert into sensorvalue (sensorid, value, timestamp)
                  values (?, ?, ?)
                ";

            if ($sensor instanceof TempSensor && $sensor->getValue() < 40) {
                $this->getContainer()["db"]->executeUpdate($query, [
                    $key,
                    $sensor->getValue(),
                    date("Y-m-d H:i")
                ]);
            } elseif ($sensor instanceof SwitchSensor) {
                $this->getContainer()["db"]->executeUpdate($query, [
                    $key,
                    (int)$sensor->getValue(),
                    date("Y-m-d H:i")
                ]);
            }
        }
    }

    /**
     * @return TempSensor[]
     */
    private function retrieveSensors()
    {
        $handle = popen('../scripts/readSensors.py', 'r');
        $json = fread($handle, 1024);
        pclose($handle);

        $sensors = [];

        if ($json !== "") {
            $jsonObject = json_decode($json);

            foreach ($jsonObject->tempsensoren as $key => $tempSensor) {
                $sensors[$key] = new TempSensor($tempSensor->Name, $tempSensor->Temp);
            }

            $sensors[] = new SwitchSensor("SproeiSensor", !(bool)$jsonObject->waterLevelSufficient);
        }

        return $sensors;
    }
}