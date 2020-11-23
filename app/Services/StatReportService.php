<?php


namespace App\Services;


class StatReportService
{
    public function getVisits($data)
    {
        $result = json_decode($data);

        // получим описание всех временных интервалов 06.2018, 07.2018 и т.д.
        $timeIntervals = $this->getTimeIntervals($result->time_intervals);

        // получим значения по метрике "посетители" для всех временных интевалов: 903000 для 06.2018, 2471000 для 07.2018 и т.д.
        $visits = $this->getVisitsTotal($result->totals[0], $timeIntervals);

        return $visits;
    }

    // получить массив с датами
    private function getTimeIntervals($data): array
    {
        $timeValueStorage = [];
        foreach($data as $dataTimesId=>$dataTimesInfo)
        {
            list($year,$month,$day) = explode('-',$dataTimesInfo[0]);
            $timeValueStorage[] = "{$month}.{$year}";
        }

        return $timeValueStorage;
    }

    // получить массив с значениями total
    private function getVisitsTotal($dataTotals, $timeValueStorage): array
    {
        $metValueStorage = [];

        $timeValueStorage = array_map(function ($value) {
            return $this->formatDate($value);
        }, $timeValueStorage);

        foreach($dataTotals as $totalId=>$totalValue) {

            $metValueStorage[$totalId][0] = $timeValueStorage[$totalId];
            $metValueStorage[$totalId][] = $totalValue;
        }

        return $metValueStorage;
    }

    private function formatDate($value)
    {
        $digitToWordArray = [
            '01'=>'Янв',
            '02'=>'Фев',
            '03'=>'Мар',
            '04'=>'Апр',
            '05'=>'Май',
            '06'=>'Июн',
            '07'=>'Июл',
            '08'=>'Авг',
            '09'=>'Сен',
            '10'=>'Окт',
            '11'=>'Ноя',
            '12'=>'Дек'
        ];

        list($month,$year) = explode('.',$value);
        $month = $digitToWordArray[$month];
        $year = $year-2000;

        return $month." ".$year;
    }
}
