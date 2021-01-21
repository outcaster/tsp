<?php

namespace App\Test\Business\Service;

use PHPUnit\Framework\TestCase;
use App\Business\TSP\City;
use App\Business\Service\CityService;

/**
 * CityServiceTest
 */
class CityServiceTest extends TestCase
{
    /**
     * @test
     * @testdox should calculate distance
     *
     * @return void
     */
    public function testCalculateDistance(): void
    {
        $service = new CityService();
        $class  = new \ReflectionClass('App\Business\Service\CityService');
        $method = $class->getMethod('calculateDistance');
        $method->setAccessible(true);

        $city1  = new City('CityA', 39.93, 116.40);
        $city2  = new City('CityB', 35.40, 139.45);

        $result =  $method->invokeArgs($service, [$city1, $city2]);

        $this->assertEquals(2084.6378, $result);
    }

    /**
     * @test
     * @testdox should parse text into an array of cities
     *
     * @return void
     */
    public function testParse(): void
    {
        $service = new CityService();
        $class  = new \ReflectionClass('App\Business\Service\CityService');
        $method = $class->getMethod('parse');
        $method->setAccessible(true);

        $json =
        'Beijing 39.93 116.40
        Tokyo 35.40 139.45
        Vladivostok 43.8 131.54';
        $result =  $method->invokeArgs($service, [$json]);

        $this->assertIsArray($result);
        $this->assertEquals(3, count($result));
        foreach ($result as $city) {
            $this->assertInstanceOf(City::class, $city);
        }
    }
}
