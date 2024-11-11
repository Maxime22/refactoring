<?php

use PHPUnit\Framework\TestCase;
use App\Example;

class ExampleTest extends TestCase
{
    private array $invoices;
    private array $plays;

    protected function setUp(): void
    {
        $this->invoices = json_decode(file_get_contents('src/Invoices.json'), true);
        $this->plays = json_decode(file_get_contents('src/Plays.json'), true);
    }

    public function testStatement()
    {
        // Arrange
        $sut = new Example();
        
        // Act
        $result = $sut->statement($this->invoices[0], $this->plays);

        // Assert
        $expectedOutput = "Statement for BigCo<br>" .
                          "Hamlet: $650.00 (55 seats)<br>" .
                          "As You Like It: $580.00 (35 seats)<br>" .
                          "Othello: $500.00 (40 seats)<br>" .
                          "Amount owed is $1,730.00<br>" .
                          "You earned 47 credits<br>";

        $this->assertSame($expectedOutput, $result);
    }
}
