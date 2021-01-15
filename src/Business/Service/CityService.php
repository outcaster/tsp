<?php
namespace App\Business\Service;

use Symfony\Component\Finder\Finder;
use App\Business\TSP\City;
use App\Business\Exception\NoContentException;


/**
 * CityService
 */
class CityService 
{ 
    /**
     * gets cities from txt file
     *
     * @return City[]
     */
    public function getCities(): array {
        $contents = null;
        $finder = new Finder();
        $finder->in('src/Business/Document')->name('citiess.txt');
        foreach ($finder as $file) {
            $contents = $file->getContents();
        }

        if ($contents == NULL) {
            throw new NoContentException('Content file not found, or empty');
        }

        return $this->parse($contents);
    } 
    
    /**
     * parse contents
     *
     * @param  mixed $contents
     * @return City[]
     */
    private function parse(string $contents): array {
        $cities = [];
        $contentsArray = explode(PHP_EOL, $contents);
        foreach ($contentsArray as $content) {
            $cityName  = '';
            $latitude  = null;
            $longitude = null;            
            $cityComponents = explode(' ', $content);
            $cityComponentLength = count($cityComponents);

            //safeguard checks could be added here 
            $latitude  = $cityComponents[$cityComponentLength-2];
            $longitude = $cityComponents[$cityComponentLength-1];
            $cityName  = $cityComponents[0];

            if ($cityComponentLength > 3) {
                for ($i=1; $i < $cityComponentLength-2; $i++) {
                    $cityName = $cityName . ' ' . $cityComponents[$i];
                }
            }  

            $cities[] = new City($cityName, $latitude, $longitude);
        }

        return $cities;
    }
}