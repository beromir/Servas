<?php

namespace App\Helpers;

use Illuminate\Support\Carbon;

class DateHelper
{
    public static function makeCarbonInstance(string $date): Carbon
    {
        return Carbon::make($date);
    }

    public static function convertToDateString(string $timestamp): string
    {
        return self::makeCarbonInstance($timestamp)->format('d.m.Y');
    }

    public static function convertToDateTimeString(string $timestamp): string
    {
        return self::makeCarbonInstance($timestamp)->format('d.m.Y H:i:s');
    }
}
