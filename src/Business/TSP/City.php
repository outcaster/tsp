<?php
declare(strict_types=1);

namespace App\Business\TSP;

/**
 * City
 */
class City {
    private string $name;
    private float $latitude;
    private float $longitude;

    /**
     * __construct
     *
     * @param  mixed $name
     * @param  mixed $latitude
     * @param  mixed $longitude
     * @return void
     */
    public function __construct(string $name, string $latitude, string $longitude)
    {
        $this->name      = $name;
        $this->latitude  = (float) $latitude;
        $this->longitude = (float) $longitude;
    }


    /**
     * Get $name
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get $latitude
     * @return float
     */
    public function getLatitude(): float
    {
        return $this->latitude;
    }

    /**
     * Get $longitude
     * @return float
     */
    public function getLongitude(): float
    {
        return $this->longitude;
    }
}
