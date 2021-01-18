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
     * @throws NoContentException
     */
    public function getCities(): array {
        $contents = null;
        $finder = new Finder();
        $finder->in('src/Business/Document')->name('cities.txt');
        foreach ($finder as $file) {
            $contents = $file->getContents();
        }

        if ($contents == NULL) {
            throw new NoContentException('Content file not found, or empty');
        }

        return $this->parse($contents);
    } 

        
    /**
     * calculate
     *
     * @param  City[] $cities
     * @return City[]
     */
    public function calculate(array $cities): array {
        $route = [];

        //calculate first triangle
        for ($i = 0; $i < 3; $i++) {
            if (!empty($cities)) {
                $city = array_shift($cities);
            }

            $city = $this->setDistance($city, $route);
            $route[$i] = $city;
        } 
        
        $firstCity = clone($route[array_key_first($route)]);
        $firstCity->setDistanceToPreviousCity($this->calculateDistance(end($route), $firstCity));
        $route[] = $firstCity;
        
        //add elements for each new city 
        while (!empty($cities)) {
            $newCity       = array_shift($cities);
            $minDistance   = null;
            $position      = null;
            $bestDistanceA = null;
            $bestDistanceB = null;
            $distanceA     = $this->calculateDistance($newCity, $route[0]);
            for ($i = 1; $i < count($route); $i++) {
                $distanceB = $distanceA;
                $distanceA = $this->calculateDistance($newCity, $route[$i]);
                if ($minDistance === null || ($distanceA + $distanceB - $route[$i]->getDistanceToPreviousCity()) < $minDistance ) {
                    $minDistance = ($distanceA + $distanceB - $route[$i]->getDistanceToPreviousCity());
                    $position    = $i; 
                    $bestDistanceA = $distanceA;
                    $bestDistanceB = $distanceB;
                }
            }

            $newCity->setDistanceToPreviousCity($bestDistanceB);
            $route[$position]->setDistanceToPreviousCity($bestDistanceA);
            array_splice($route, $position, 0, [$newCity]); 
        }

        $route = $this->finalize($route);

        return $route;
    }

        
    /**
     * finalize
     *
     * @param  City[] $route
     * @return City[]
     */
    private function finalize(array $route): array {
        if ($route[1]->getDistanceToPreviousCity() < end($route)->getDistanceToPreviousCity()) {
            array_pop($route);
            
            return $route;
        }

        array_shift($route);
        $route = array_reverse($route);
        $route[0]->getDistanceToPreviousCity(0);
        
        return $route;
    }
  
    /**
     * setDistance
     *
     * @param  mixed $city
     * @param  mixed $route
     * @return City
     */
    private function setDistance(City $city, array $route): City {
        if (count($route) === 0) {
            $city->setDistanceToPreviousCity(0);
            
            return $city;
        }

        $previousCity = end($route);
        $city->setDistanceToPreviousCity($this->calculateDistance($city, $previousCity));

        return $city;
    }
        
    /**
     * calculateDistance
     *
     * @param  mixed $cityA
     * @param  mixed $cityB
     * @return float
     */
    private function calculateDistance(City $cityA, City $cityB): float {
        $deltaLat = $cityB->getLatitude() - $cityA->getLatitude();
        $deltaLon = $cityB->getLongitude() - $cityA->getLongitude();

        $earthRadius = 6372.795477598;

        $alpha    = $deltaLat/2;
        $beta     = $deltaLon/2;
        $a        = sin(deg2rad($alpha)) * sin(deg2rad($alpha)) + cos(deg2rad($cityA->getLatitude())) * cos(deg2rad($cityB->getLatitude())) * sin(deg2rad($beta)) * sin(deg2rad($beta)) ;
        $c        = asin(min(1, sqrt($a)));
        $distance = 2*$earthRadius * $c;
        $distance = round($distance, 4);

        return $distance;
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