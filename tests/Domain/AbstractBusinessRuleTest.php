<?php

declare(strict_types=1);

namespace Neuron\BuildingBlocks\Tests\Domain;

use Neuron\BuildingBlocks\Domain\AbstractBusinessRule;
use Neuron\BuildingBlocks\Domain\BusinessRuleInterface;
use PHPUnit\Framework\TestCase;

class AbstractBusinessRuleTest extends TestCase
{
    private BusinessRuleInterface $businessRule;

    public function testThatIsBrokenReturnsTrueWhenProvidedParametersAreIncorrect(): void
    {
        $this->businessRule = new TestBusinessRule('incorrect');

        $this->assertTrue($this->businessRule->isBroken());
    }

    public function testThatIsBrokenReturnsCorrectMessage(): void
    {
        $this->businessRule = new TestBusinessRule('incorrect');

        $this->assertEquals('Provided param \'incorrect\' is incorrect', $this->businessRule->getMessage());
    }

    public function testThatIsBrokenReturnsFalseWhenProvidedParametersAreCorrect(): void
    {
        $this->businessRule = new TestBusinessRule('correct');

        $this->assertFalse($this->businessRule->isBroken());
    }
}

class TestBusinessRule extends AbstractBusinessRule
{
    public function __construct(
        private readonly string $someParam
    ) {
    }

    public function isBroken(): bool
    {
        return 'correct' !== $this->someParam;
    }

    public function getMessageTemplate(): string
    {
        return 'Provided param \'%s\' is incorrect';
    }

    public function getMessageArguments(): array
    {
        return [$this->someParam];
    }
}
