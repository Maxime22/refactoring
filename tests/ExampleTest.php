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

    public function testStatementWithLowAudience()
    {
        // Arrange
        $sut = new Example();
        $invoice = [
            "customer" => "SmallCo",
            "performances" => [
                ["playID" => "hamlet", "audience" => 5],
                ["playID" => "as-like", "audience" => 15],
                ["playID" => "othello", "audience" => 10]
            ]
        ];
        $plays = [
            "hamlet" => ["name" => "Hamlet", "type" => "tragedy"],
            "as-like" => ["name" => "As You Like It", "type" => "comedy"],
            "othello" => ["name" => "Othello", "type" => "tragedy"]
        ];

        // Act
        $result = $sut->statement($invoice, $plays);

        $expectedOutput = "Statement for SmallCo<br>" .
                        "Hamlet: $400.00 (5 seats)<br>" .
                        "As You Like It: $345.00 (15 seats)<br>" .
                        "Othello: $400.00 (10 seats)<br>" .
                        "Amount owed is $1,145.00<br>" .
                        "You earned 3 credits<br>";

        // Assert
        $this->assertSame($expectedOutput, $result);
    }

    public function testStatementWithAudienceZero()
    {
        // Arrange
        $sut = new Example();
        $invoice = [
            "customer" => "LowAudienceCo",
            "performances" => [
                ["playID" => "hamlet", "audience" => 0]
            ]
        ];
        $plays = [
            "hamlet" => ["name" => "Hamlet", "type" => "tragedy"]
        ];

        // Act
        $result = $sut->statement($invoice, $plays);
        $expectedOutput = "Statement for LowAudienceCo<br>" .
                        "Hamlet: $400.00 (0 seats)<br>" .
                        "Amount owed is $400.00<br>" .
                        "You earned 0 credits<br>";

        // Assert
        $this->assertSame($expectedOutput, $result);
    }

    public function testStatementWithAudienceAtThresholds()
    {
        // Arrange
        $sut = new Example();
        $invoice = [
            "customer" => "ThresholdCo",
            "performances" => [
                ["playID" => "hamlet", "audience" => 30],
                ["playID" => "as-like", "audience" => 20]
            ]
        ];
        $plays = [
            "hamlet" => ["name" => "Hamlet", "type" => "tragedy"],
            "as-like" => ["name" => "As You Like It", "type" => "comedy"]
        ];

        // Act
        $result = $sut->statement($invoice, $plays);
        $expectedOutput = "Statement for ThresholdCo<br>" .
                        "Hamlet: $400.00 (30 seats)<br>" .
                        "As You Like It: $360.00 (20 seats)<br>" .
                        "Amount owed is $760.00<br>" .
                        "You earned 4 credits<br>";

        // Assert
        $this->assertSame($expectedOutput, $result);
    }

    public function testStatementWithUnknownPlayType()
    {
        // Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Unknown type mystery");

        // Arrange
        $sut = new Example();
        $invoice = [
            "customer" => "UnknownPlayTypeCo",
            "performances" => [
                ["playID" => "mystery-play", "audience" => 20]
            ]
        ];
        $plays = [
            "mystery-play" => ["name" => "Mystery Play", "type" => "mystery"]
        ];

        // Act
        $sut->statement($invoice, $plays);
    }

    public function testStatementWithNoPerformances()
    {
        // Arrange
        $sut = new Example();
        $invoice = [
            "customer" => "EmptyCo",
            "performances" => []
        ];
        $plays = [];

        // Act
        $result = $sut->statement($invoice, $plays);
        $expectedOutput = "Statement for EmptyCo<br>" .
                        "Amount owed is $0.00<br>" .
                        "You earned 0 credits<br>";

        // Assert
        $this->assertSame($expectedOutput, $result);
    }

    public function testStatementWithNegativeAudience()
    {
        // Arrange
        $sut = new Example();
        $invoice = [
            "customer" => "NegativeAudienceCo",
            "performances" => [
                ["playID" => "hamlet", "audience" => -5]
            ]
        ];
        $plays = [
            "hamlet" => ["name" => "Hamlet", "type" => "tragedy"]
        ];

        // Act
        $result = $sut->statement($invoice, $plays);
        $expectedOutput = "Statement for NegativeAudienceCo<br>" .
                        "Hamlet: $400.00 (-5 seats)<br>" .
                        "Amount owed is $400.00<br>" .
                        "You earned 0 credits<br>";

        // Assert
        $this->assertSame($expectedOutput, $result);
    }

}
