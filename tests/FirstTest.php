<?php

use PHPUnit\Framework\TestCase;
use App\First;

class FirstTest extends TestCase
{
    public function testCoucou()
    {
        $first = new First();
        $this->expectOutputString("Coucou, tout fonctionne !");
        $first->coucou();
    }
}
