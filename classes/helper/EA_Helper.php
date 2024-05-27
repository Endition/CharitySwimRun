<?php

namespace EndeAuswertung\classes\helper;


class EA_Helper
{
    static function getProzent(int $max, int $anteil): float
    {
        return round($anteil / $max * 100, 0);
    }

    /*
     * Funtkion um einen Timestamp in HH:MM:SS umzuwandeln
     */
    static function FormatterRundenzeit(int $timestamp): string
    {
        $gesamtminuten = floor($timestamp / 60); // wird immer abgerundet weil der rest ja minuten sind
        $stunden = floor($gesamtminuten / 60);
        $sekunden = $timestamp % 60;
        $minuten = $gesamtminuten % 60;
        $zeit = str_pad($stunden, 2, 0, STR_PAD_LEFT) . ":" . str_pad($minuten, 2, 0, STR_PAD_LEFT) . ":" . str_pad($sekunden, 2, 0, STR_PAD_LEFT);
        return $zeit;
    }
}
