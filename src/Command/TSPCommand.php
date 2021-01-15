<?php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;


class TSPCommand extends Command
{
    protected static $defaultName = 'app:tsp';

    protected function configure()
    {
        $this
            ->setDescription('Solves the TSP according to the cities.txt file stored on src/Business/Documents.')
            ->setHelp('Just run the command!');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $finder = new Finder();
        $finder->in('src/Business/Documents')->name('cities.txt');
        foreach($finder as $file) {
            $contents = $file->getContents();
        }
       
        die(var_dump($contents));

        // return this if there was no problem running the command
        // (it's equivalent to returning int(0))
        return Command::SUCCESS;

        // or return this if some error happened during the execution
        // (it's equivalent to returning int(1))
        // return Command::FAILURE;
    }
}