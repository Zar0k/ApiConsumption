<?php
declare(strict_types=1);
namespace App\Factory;
use App\Model\Country;
use App\Validator\ArrayPropertiesValidatorInterface;
use InvalidArgumentException;

class CountryFactory implements CountryFactoryInterface
{
    /**
     * @var ArrayPropertiesValidatorInterface
     */
    private $arrayPropertiesValidator;
    /**
     * @param ArrayPropertiesValidatorInterface $arrayPropertiesValidator
     */
    public function __construct(ArrayPropertiesValidatorInterface $arrayPropertiesValidator)
    {
        $this->arrayPropertiesValidator = $arrayPropertiesValidator;
    }
    /**
     * @param array $array
     *
     * @throws InvalidArgumentException if a Country could not be instantiated from the given array
     *
     * @return Country
     */
    public function createFromArray(array $array): Country
    {
        $errorMessage = $this->arrayPropertiesValidator->validate(['name', 'alpha2Code'], $array);
        if ($errorMessage !== null) {
            throw new InvalidArgumentException(sprintf('Cannot create Country: %s', $errorMessage));
        }

        foreach ($array as $value) {
            return new Country($value['name'], $value['alpha2Code'], $value['languages'][0]['iso639_1']);
        }
    }
}
