<?php
namespace App\Business\Service;

use Symfony\Component\Finder\Finder;

use App\Business\TSP\City;


/**
 * CityService
 */
class CityService 
{
    public function getCities(): array {
        $contents = [];
        $finder = new Finder();
        $finder->in('src/Business/Document')->name('cities.txt');
        foreach($finder as $file) {
            $contents = $file->getContents();
        }

        return $this->parse($contents);
    } 

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