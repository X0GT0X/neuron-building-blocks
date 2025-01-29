<?php

declare(strict_types=1);

namespace Neuron\BuildingBlocks\Tests\Domain;

use Neuron\BuildingBlocks\Domain\AbstractBusinessRule;
use PHPUnit\Framework\TestCase;

class AbstractBusinessRuleTest extends TestCase
{
    public function testThatIsBrokenReturnsTrueWhenProvidedParametersAreIncorrect(): void
    {
        $businessRule = new TestBusinessRule('incorrect');

        $this->assertTrue($businessRule->isBroken());
    }

    public function testThatIsBrokenReturnsCorrectMessage(): void
    {
        $businessRule = new TestBusinessRule('incorrect');

        $this->assertEquals('Provided param \'incorrect\' is incorrect', $businessRule->getMessage());
        $this->assertEquals(['incorrect'], $businessRule->getMessageArguments());
    }

    public function testThatIsBrokenReturnsFalseWhenProvidedParametersAreCorrect(): void
    {
        $businessRule = new TestBusinessRule('correct');

        $this->assertFalse($businessRule->isBroken());
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
