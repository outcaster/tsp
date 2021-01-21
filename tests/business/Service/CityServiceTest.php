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
     * @testdox should get the cities
     *
     * @return void
     */
    public function testGetCities(): void
    {
        $service = new CityService();
        $result = $service->getCities();

        $this->assertIsArray($result);
        $this->assertEquals(32, count($result));
        foreach ($result as $city) {
            $this->assertInstanceOf(City::class, $city);
        }
    }

    /**
     * @test
     * @testdox should return a route
     */
    public function testCalculate(): void
    {
        $service = new CityService();
        $cities  = [
            new City('Las Venturas', 39.93, 116.40),
            new City('Los Santos', 35.40, 139.45),
            new City('Sanfierro', 43.8, 131.54)
        ];

        $result = $service->calculate($cities);

        $this->assertIsArray($result);
        $this->assertEquals(3, count($result));
        foreach ($result as $city) {
            $this->assertInstanceOf(City::class, $city);
        }
    }

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
        'Las Venturas 39.93 116.40
        Los Santos 35.40 139.45
        Sanfierro 43.8 131.54';
        $result =  $method->invokeArgs($service, [$json]);

        $this->assertIsArray($result);
        $this->assertEquals(3, count($result));
        foreach ($result as $city) {
            $this->assertInstanceOf(City::class, $city);
        }
    }
}
