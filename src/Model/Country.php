<?php
declare(strict_types=1);
namespace App\Model;
class Country
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $countryIso;

    /**
     * @var string
     */
    private $countryIso639;

    /**
     * @param string $name
     * @param string $countryIso
     * @param string $countryIso639
     */
    public function __construct(string $name, string $countryIso, string $countryIso639)
    {
        $this->name = $name;
        $this->countryIso = $countryIso;
        $this->countryIso639 = $countryIso639;
    }
    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getCountryIso(): string
    {
        return $this->countryIso;
    }

    /**
     * @return string
     */
    public function getCountryIso639(): string
    {
        return $this->countryIso639;
    }
}
