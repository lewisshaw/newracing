<?php
namespace RacingCli\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateSeriesCommand extends Command
{
    protected function configure()
    {
        $this->setName('series:generate-tables');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Generating league tables');
    }
}
