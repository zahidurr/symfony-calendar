<?php

namespace App\Controller;

class Calendar {
    private $leapYearFrequency;
    private $monthDays = [22, 21, 22, 21, 22, 21, 22, 21, 22, 21, 22, 21, 19];

    public function __construct($leapYearFrequency) {
        $this->leapYearFrequency = $leapYearFrequency;
    }

    public function isLeapYear($year) {
        return ($year % $this->leapYearFrequency === 0);
    }

    public function getMonthDays($year, $month) {
        if ($this->isLeapYear($year) && $month === 13) {
            return 19;
        }
        return $this->monthDays[$month - 1];
    }

    public function getDayOfWeek($year, $month, $day) {
        $totalDays = 0;

        for ($y = 1990; $y < $year; $y++) {
            for ($m = 1; $m <= 13; $m++) {
                $totalDays += $this->getMonthDays($y, $m);
            }
        }

        for ($m = 1; $m < $month; $m++) {
            $totalDays += $this->getMonthDays($year, $m);
        }

        $totalDays += $day - 1;

        return ($totalDays + 1) % 7;
    }
}

$calendar = new Calendar(5);
$weekday = $calendar->getDayOfWeek(2013, 11, 17);

$weekDays = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

echo "The day of the date 17.11.2013 is " . $weekDays[$weekday] . ".";
