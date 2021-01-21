<?php
declare(strict_types=1);

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
    public function getCities(): array
    {
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
    public function calculate(array $cities): array
    {
        $route[] = array_shift($cities);
        $route[0]->setDistanceToPreviousCity(0);

        while (!empty($cities)) {
            $nextCityIndex = null;
            $nextCityDistance = null;
            for ($i = 0; $i < count($cities); $i++) {
                $distance = $this->calculateDistance(end($route), $cities[$i]);

                if ($nextCityIndex === null || $distance < $nextCityDistance) {
                    $nextCityIndex = $i;
                    $nextCityDistance = $distance;
                }
            }

            $route[] = $cities[$nextCityIndex];
            end($route)->setDistanceToPreviousCity($nextCityDistance);
            unset($cities[$nextCityIndex]);
            $cities = array_values($cities);
        }

        return $route;
    }

    /**
     * calculateDistance
     *
     * @param  mixed $cityA
     * @param  mixed $cityB
     * @return float
     */
    private function calculateDistance(City $cityA, City $cityB): float
    {
        $deltaLat = $cityB->getLatitude() - $cityA->getLatitude();
        $deltaLon = $cityB->getLongitude() - $cityA->getLongitude();

        $earthRadius = 6372.795477598;

        $alpha    = $deltaLat/2;
        $beta     = $deltaLon/2;
        $a        = sin(deg2rad($alpha))
            * sin(deg2rad($alpha))
            + cos(deg2rad($cityA->getLatitude()))
            * cos(deg2rad($cityB->getLatitude()))
            * sin(deg2rad($beta))
            * sin(deg2rad($beta)) ;
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
    private function parse(string $contents): array
    {
        $cities = [];
        $contentsArray = explode(PHP_EOL, $contents);
        foreach ($contentsArray as $content) {
            $cityName  = '';
            $latitude  = null;
            $longitude = null;
            $cityComponents = explode(' ', $content);
            $cityComponentLength = count($cityComponents);

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
