<?php
declare(strict_types=1);
namespace App\Tests\Decoder;
use App\Validator\ArrayPropertiesValidator;
use PHPUnit\Framework\TestCase;
final class ArrayPropertiesValidatorTest extends TestCase
{
    /**
     * @var ArrayPropertiesValidator
     */
    private $arrayPropertiesValidator;
    protected function setUp(): void
    {
        $this->arrayPropertiesValidator = new ArrayPropertiesValidator();
    }
    /**
     * @covers ArrayPropertiesValidator::createFromArray
     */
    public function testCreateFromArray(): void
    {
        $array = [
            [
            'name' => 'Spain',
            'iso2Code' => 'es'
            ]
        ];
        $result = $this->arrayPropertiesValidator->validate(['name', 'iso2Code'], $array);
        $this->assertNull($result);
    }
    /**
     * @covers ArrayPropertiesValidator::createFromArray
     */
    public function testCreateFromArrayWithMissingProperty(): void
    {
        $array = [
            ['iso2Code' => 'es'],
        ];
        $result = $this->arrayPropertiesValidator->validate(['name', 'iso2Code'], $array);
        $this->assertEquals('Missing value for name', $result);
    }
}
