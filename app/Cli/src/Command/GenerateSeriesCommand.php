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
        //get all races in a series
        //for each race, get each competitor's result
        //Fill these into a table
        //use runtime calculations for scoring
        //
        //Also could queue small changes so they can update rather than regen everything
    }
}
