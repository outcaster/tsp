<?php
namespace App\Command;

use App\Business\Service\CityService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * TSPCommand
 */
class TSPCommand extends Command
{
    protected static $defaultName = 'app:tsp';
    private CityService $cityService;
    
    /**
     * __construct
     *
     * @param  mixed $cityService
     * @return void
     */
    public function __construct(CityService $cityService) {
        $this->cityService = $cityService;
        parent::__construct();
    }
    
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
        $cities = $this->cityService->getCities();
        die(var_dump($cities));
        // return this if there was no problem running the command
        // (it's equivalent to returning int(0))
        return Command::SUCCESS;

        // or return this if some error happened during the execution
        // (it's equivalent to returning int(1))
        // return Command::FAILURE;
    }
}