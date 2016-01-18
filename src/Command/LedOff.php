<?php

namespace LWS\Palu\Command;

use Cilex\Command\Command;
use PHPushbullet\PHPushbullet;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\LockHandler;

class LedOff extends Command
{
    public function configure()
    {
        $this
            ->setName('Leds-off')
            ->setDescription('Starts the process of turning off the lamps');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $lockHandler = new LockHandler("ledsOff");

        for ($i = 20; $i > 0; $i--) {
            if ($i % 2 == 0) {
                shell_exec("/var/www/html/scripts/milight_white.py 1 down");
            }

            shell_exec("/var/www/html/scripts/milight_rgb.py 1 b $i");
            sleep(360);
        }

        shell_exec("/var/www/html/scripts/milight_white.py 1 off");
        shell_exec("/var/www/html/scripts/milight_rgb.py 1 off");

        $lockHandler->release();
    }
}