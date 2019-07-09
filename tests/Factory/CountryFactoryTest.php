<?php
declare(strict_types=1);
namespace App\Tests\Decoder;
use App\Factory\CountryFactory;
use App\Validator\ArrayPropertiesValidatorInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
final class CountryFactoryTest extends TestCase
{
    /**
     * @var ArrayPropertiesValidatorInterface|MockObject
     */
    private $arrayPropertiesValidator;
    /**
     * @var CountryFactory
     */
    private $countryFactory;
    protected function setUp(): void
    {
        $this->arrayPropertiesValidator = $this->getMockBuilder(ArrayPropertiesValidatorInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->countryFactory = new CountryFactory($this->arrayPropertiesValidator);
    }

    /**
     * @covers  CountryFactory::createFromArray
     */
    public function testCreateFromInvalidArray(): void
    {
        $array = [
            'test'  => 'fake',
            'name'  => 'Spain'
        ];
        $this->arrayPropertiesValidator
            ->expects($this->once())
            ->method('validate')
            ->with(['name', 'alpha2Code'], $array)
            ->willReturn('Missing value for alpha2Code');
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot create Country: Missing value for alpha2Code');
        $this->countryFactory->createFromArray($array);
    }
}