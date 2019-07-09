<?php
declare(strict_types=1);
namespace App\Factory;
use App\Model\Country;
use InvalidArgumentException;

interface CountryFactoryInterface
{
    /**
     * @param array $array
     *
     * @throws InvalidArgumentException if a Country could not be instantiated from the given array
     *
     * @return Country
     */
    public function createFromArray(array $array): Country;
}