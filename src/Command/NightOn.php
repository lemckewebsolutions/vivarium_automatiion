<?php

namespace LWS\Palu\Command;

use Cilex\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class NightOn extends Command
{
    public function configure()
    {
        $this
            ->setName('Night-on')
            ->setDescription('Starts the night');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        shell_exec("/var/www/html/scripts/milight_rgb.py 1 c 1");
        usleep(100);
        shell_exec("/var/www/html/scripts/milight_rgb.py 1 b 3");
        sleep(7200);
        shell_exec("/var/www/html/scripts/milight_rgb.py 1 b 1");
        usleep(100);
        shell_exec("/var/www/html/scripts/milight_rgb.py 1 off");
    }
}