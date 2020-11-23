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

    public function getInteres($data)
    {
        $result = json_decode($data);

        // общее количество
        $total = 0;

        foreach($result->data as $id => $content) {

            // название интереса: туризм, обустройство и т.д.
            $dimensions = $content->dimensions;

            // значение метрики "визиты" для интереса туризм, обустройство и т.д.
            $metrics = $content->metrics;

            // формируем результирующий массив: название интереса, кол-во визитов
            foreach($dimensions as $dimId => $dim) {
                $total += $metrics[0];
                $interes[] = [$dim->name, (int)$metrics[0]];

            }


        }

        return $interes;
    }

    public function getGender($data)
    {
        $result = json_decode($data);

        $woman = round($result->totals[0][0],1);
        $man = round($result->totals[1][0],1);

        $gender[] = ['Женский',$woman];
        $gender[] = ['Мужской',$man];

        return $gender;
    }

    public function getAge($data)
    {
        $result = json_decode($data);

        $ageValues = array_map(function ($value) {
            return round($value[0],1);
        }, $result->totals);

        // получим все описания метрик - младше 18, 18-24 и т.д.
        $dimensionNames = $this->getDimensionName($result->data);

        $age = [];

        // сформируем результирующий массив - метрики и проценты
        foreach($dimensionNames as $index => $dimensionName) {
            // если всех метрик больше, чем в элементов в valueStorage
            if($index == count($ageValues)) break;

            $age[] = [$dimensionName,$ageValues[$index]];
        }

        return $age;
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

    // получить массив с значениями разделителей (прямые заходы, поисковые системы, etc)
    private function getDimensionName($dataObject): array
    {
        $dimNameStorage = [];

        foreach($dataObject as $dataObjectId => $dataObjectInfo)
        {

            // группировки
            $dimensions = $dataObjectInfo->dimensions;
            // имя группировки
            $dimensionsName = $dimensions[0]->name;
            //хранилище имен
            $dimNameStorage[] = $dimensionsName;

        }

        return $dimNameStorage;
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
