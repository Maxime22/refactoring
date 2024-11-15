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

        $playFor = function($aPerformance) use ($plays) {
            return $plays[$aPerformance['playID']];
        };

        $amountFor = function ($aPerformance) use ($playFor){
            $result = 0;
            switch ($playFor($aPerformance)['type']) {
                case "tragedy":
                    $result = 40000;
                    if ($aPerformance['audience'] > 30) {
                        $result += 1000 * ($aPerformance['audience'] - 30);
                    }
                    break;
                case "comedy":
                    $result = 30000;
                    if ($aPerformance['audience'] > 20) {
                        $result += 10000 + 500 * ($aPerformance['audience'] - 20);
                    }
                    $result += 300*$aPerformance['audience'];
                    break;
                default:
                    throw new \Exception("Unknown type {$playFor($aPerformance)['type']}");
                    break;
            }
            return $result;
        };

        foreach ($invoice['performances'] as $perf) {
            $thisAmount = $amountFor($perf);
            
            // ajoute des crédits de volume
            $volumeCredits += max($perf['audience'] - 30, 0);
            if("comedy" === $playFor($perf)['type']){
                $volumeCredits += floor($perf['audience'] / 5);
            }
            // imprime la ligne de cette commande
            $result .= "{$playFor($perf)['name']}: " . $format($thisAmount / 100) . " ({$perf['audience']} seats)<br>";
            $totalAmount += $thisAmount;
        }
        $result .= "Amount owed is ".$format($totalAmount/100)."<br>";
        $result .= "You earned ".$volumeCredits." credits<br>";
        return $result;
    }

}
