<?php

namespace App;

class Example
{
    public function statement($invoice, $plays){
        $totalAmount = 0;
        $volumeCredits = 0;
        $result = "Statement for {$invoice['customer']}<br>";

        $format = function ($number) {
            return '$' . number_format($number, 2, '.', ',');
        };

        foreach ($invoice['performances'] as $perf) {
            $play = $plays[$perf['playID']];
            $thisAmount = 0;
        
            switch ($play['type']) {
                case "tragedy":
                    $thisAmount = 40000;
                    if ($perf['audience'] > 30) {
                        $thisAmount += 1000 * ($perf['audience'] - 30);
                    }
                    break;
                case "comedy":
                    $thisAmount = 30000;
                    if ($perf['audience'] > 20) {
                        $thisAmount += 10000 + 500 * ($perf['audience'] - 20);
                    }
                    $thisAmount += 300*$perf['audience'];
                    break;
                default:
                    throw new \Exception("Unknown type {$play['type']}");
                    break;
            }
            // ajoute des crédits de volume
            $volumeCredits += max($perf['audience'] - 30, 0);
            if("comedy" === $play['type']){
                $volumeCredits += floor($perf['audience'] / 5);
            }
            // imprime la ligne de cette commande
            $result .= "{$play['name']}: " . $format($thisAmount / 100) . " ({$perf['audience']} seats)<br>";
            $totalAmount += $thisAmount;
        }
        $result .= "Amount owed is ".$format($totalAmount/100)."<br>";
        $result .= "You earned ".$volumeCredits." credits<br>";
        return $result;
    }
}
