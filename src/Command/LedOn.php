<?php

namespace LWS\Palu\Command;

use Cilex\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LedOn extends Command
{
    public function configure()
    {
        $this
            ->setName('Leds-on')
            ->setDescription('Starts the process of turning off the lamps');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        shell_exec("/var/www/html/scripts/milight_rgb.py 1 b 1");
        usleep(100);
        shell_exec("/var/www/html/scripts/milight_rgb.py 1 W");

        for ($i = 0; $i < 20; $i++) {

            if ($i % 2 == 0) {
                shell_exec("/var/www/html/scripts/milight_white.py 1 up");
            }

            shell_exec("/var/www/html/scripts/milight_rgb.py 1 b $i");
            sleep(360);
        }
    }
}