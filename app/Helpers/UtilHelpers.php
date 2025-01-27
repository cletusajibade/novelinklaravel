<?php

namespace App\Helpers;

use Carbon\Carbon;

class UtilHelpers
{
    /**
     * A better way to dump variables to the browser
     */
    public static function dump()
    {
        $args = func_get_args();

        echo "\n<pre style=\"border:1px solid #ccc;padding:10px;" .
            "margin:10px;font:14px courier;background:whitesmoke;" .
            "display:block;border-radius:4px;\">\n";

        $trace = debug_backtrace(false);
        $offset = (@$trace[2]['function'] === 'dump_d') ? 2 : 0;

        echo "<span style=\"color:red\">" .
            @$trace[1 + $offset]['class'] . "</span>:" .
            "<span style=\"color:blue;\">" .
            @$trace[1 + $offset]['function'] . "</span>:" .
            @$trace[0 + $offset]['line'] . " " .
            "<span style=\"color:green;\">" .
            @$trace[0 + $offset]['file'] . "</span>\n";

        if (!empty($args)) {
            call_user_func_array('var_dump', $args);
        }

        echo "</pre>\n";
    }

    public static function dump_d()
    {
        call_user_func_array('dump', func_get_args());
        die();
    }

    public static function generateDatesWithTimes($startDate, $endDate, $startTime, $endTime)
    {
        $datesWithTimes = [];

        // Convert inputs to Carbon instances
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);
        $startTime = Carbon::parse($startTime);
        $endTime = Carbon::parse($endTime);

        // Loop through each date
        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            $currentDateTimes = [];

            // Loop through times for the current date
            for ($time = $startTime->copy(); $time->lt($endTime); $time->addHour()) {
                $currentDateTimes[] = $time->toTimeString();
            }

            $datesWithTimes[$date->toDateString()] = $currentDateTimes;
        }

        return $datesWithTimes;
    }

    /**
     * Get dates and times between two given dates excluding weekends.
     *
     * @param string $startDate Start date in 'Y-m-d' format.
     * @param string $endDate End date in 'Y-m-d' format.
     * @param string $startTime Start time in 'H:i' format.
     * @param string $endTime End time in 'H:i' format.
     * @param bool $excludeWeekends
     * @return array
     */
    public static function getDatesAndTimes($startDate, $endDate, $startTime, $endTime, $excludeWeekends = true, $duration = 1)
    {
        $dates = [];
        $start = Carbon::createFromFormat('Y-m-d', $startDate);
        $end = Carbon::createFromFormat('Y-m-d', $endDate);
        $timeStart = Carbon::createFromFormat('H:i', $startTime);
        $timeEnd = Carbon::createFromFormat('H:i', $endTime);
        $interval = $duration * 60; // convert to minutes

        while ($start->lte($end)) {
            if ($excludeWeekends) {
                if (!$start->isWeekend()) {
                    $times = [];
                    $current = $timeStart->copy();
                    while ($current->lt($timeEnd)) {
                        $times[] = $current->format('H:i');
                        $current->addMinutes($interval);
                    }
                    $dates[] = [
                        'date' => $start->toDateString(),
                        'times' => $times,
                    ];
                }
            } else {
                $times = [];
                $current = $timeStart->copy();
                while ($current->lt($timeEnd)) {
                    $times[] = $current->format('H:i');
                    $current->addMinutes($interval);
                }
                $dates[] = [
                    'date' => $start->toDateString(),
                    'times' => $times,
                ];
            }

            $start->addDay();
        }

        return $dates;
    }

    public static function convertMstToClientTz($mstTimeString, $timezone, $locale)
    {
        $mstTime = Carbon::createFromFormat('H:i:s', $mstTimeString, 'America/Denver');

        // Get the three-letter timezone abbreviation
        $carbonDate = Carbon::now($timezone);
        $timezoneAbbreviation = $carbonDate->format('T');

        // Convert to client's timezone and return
        return $mstTime->setTimezone($timezone)->format('H:i') . " " .
            $timezoneAbbreviation;
    }
}
