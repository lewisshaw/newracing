<?php
namespace Racing\Filters;

class SecsToMins
{
    public function filter($seconds)
    {
        $decimalMinutes = bcdiv($seconds, 60, 10);
        $minutes = intval($decimalMinutes);
        $decimalSeconds = bcsub($decimalMinutes, $minutes, 10);
        $seconds = round(bcmul($decimalSeconds, 60, 10));
        if ($seconds >= 60) {
            $seconds = 0;
            $minutes++;
        }
        return [
            'seconds' => str_pad($seconds, 2, "0", STR_PAD_LEFT),
            'minutes' => str_pad($minutes, 2, "0", STR_PAD_LEFT),
        ];
    }
}
