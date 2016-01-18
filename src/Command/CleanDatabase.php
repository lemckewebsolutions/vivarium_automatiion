<?php

namespace LWS\Palu\Command;

use Cilex\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CleanDatabase extends Command
{

    public function configure()
    {
        $this
            ->setName('cleanDB')
            ->setDescription('Removes all the old values from the database');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $query = "
          delete
          from
            sensorvalue
          where
            timestamp < date_sub(now(), interval 7 day)
        ";

        $deletedRows = $this->getContainer()["db"]->executeUpdate($query);

        $output->writeln($deletedRows . " rows deleted");
    }
}