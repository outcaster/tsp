<?php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

/**
 * TSPCommand
 */
class TSPCommand extends Command
{
    protected static $defaultName = 'app:tsp';
    
    /**
     * configure
     *
     * @return void
     */
    protected function configure(): void {
        $this
            ->setDescription('Solves the TSP according to the cities.txt file stored on src/Business/Documents.')
            ->setHelp('Just run the command!');
    }

        
    /**
     * execute
     *
     * @param  mixed $input
     * @param  mixed $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int {
        $cities = $this->getCities(); 
        die(var_dump($cities));
        // return this if there was no problem running the command
        // (it's equivalent to returning int(0))
        return Command::SUCCESS;

        // or return this if some error happened during the execution
        // (it's equivalent to returning int(1))
        // return Command::FAILURE;
    }

        
    /**
     * getCities
     *
     * @return string
     */
    private function getCities(): string {
        $contents = [];
        $finder = new Finder();
        $finder->in('src/Business/Document')->name('cities.txt');
        foreach($finder as $file) {
            $contents = $file->getContents();
        }

        return $contents;
    } 
}