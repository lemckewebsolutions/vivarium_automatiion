<?php

namespace LWS\Palu\Command;

use Cilex\Command\Command;
use LWS\Palu\Relay\RelaySwitch;
use LWS\Palu\Relay\TimeTableEntry;
use LWS\Palu\Repository\RelaySwitchRepository;
use LWS\Palu\Repository\SensorRepository;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SwitchRelay extends Command
{
    public function configure()
    {
        $this
            ->setName('SwitchRelay')
            ->setDescription('Set the relay switches to the correct state according their timetable,');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $relayRepo = new RelaySwitchRepository($this->getApplication()->getContainer());
        $switches = $relayRepo->retrieveSwitches();

        foreach ($switches as $switch) {
            if ($switch->getTimeTable()->isAutoPilot() === false) {
                continue;
            }

            $activeEntries = array_filter(
                $switch->getTimeTable()->getTimeTableEntries(),
                function (TimeTableEntry $timeTableEntry)
                {
                    return (time() >= strtotime($timeTableEntry->getFrom()) &&
                    time() <= strtotime($timeTableEntry->getTo()));
            });

            if (count($activeEntries) > 0 || $switch->getTimeTable()->isAlwaysOn() === true) {
                if ($this->switchCanBeTurnedOn($switch) === false) {
                    $output->writeln($switch->getName() . " kan niet worden ingeschakeld");

                    $switch->turnOff();
                    continue;
                }

                $switch->turnOn();
            } else {
                $switch->turnOff();
            }
        }
    }

    /**
     * @param RelaySwitch $switch
     * @return bool
     */
    private function switchCanBeTurnedOn(RelaySwitch $switch)
    {
        $canGoOn = true;

        if ($switch->isCheckSensor() === true) {
            $sensorRepo = new SensorRepository($this->getApplication()->getContainer());
            $pushBullet = $this->getApplication()->getContainer()["pushBullet"];

            $sensor = $sensorRepo->retrieveLastValueForSensor($switch->getSensorId());

            switch ($sensor->getName()) {
                case 'Sproeier':
                    if ($sensor->getValue() === false) {
                        $pushBullet->all()->note("Faal", "Reservoir sproeisysteem leeg.");
                    }
                    return $sensor->getValue();
                case 'Water':
                    return $sensor->getValue() <= $this->getMaxWaterTemperature();
                case "Warmtespot":
                    if ($sensor->getValue() > $this->getMaxLandTemperature()) {
                        $pushBullet->all()->note("Opgepast", "Warmtelamp uitgezet vanwege te hoge lang temperatuur.");
                    }
                    return $sensor->getValue() <= $this->getMaxLandTemperature();
            }

            if ($sensor->getValue() === false) {
                $canGoOn = false;
            }
        }

        return $canGoOn;
    }

    private function getMaxLandTemperature()
    {
        /* @var \LWS\Palu\Temperature\MaxLandTemperatureSchema $maxLandTemperaturesSchema */
        $maxLandTemperaturesSchema = $this->getApplication()->getContainer()["MaxLandTemperatureSchema"];

        return $maxLandTemperaturesSchema->getSchema()[(int)date('m')];
    }

    private function getMaxWaterTemperature()
    {
        /* @var \LWS\Palu\Temperature\MaxWaterTemperatureSchema $maxWaterTemperaturesSchema */
        $maxWaterTemperaturesSchema = $this->getApplication()->getContainer()["MaxWaterTemperatureSchema"];

        return $maxWaterTemperaturesSchema->getSchema()[(int)date('m')];
    }
}