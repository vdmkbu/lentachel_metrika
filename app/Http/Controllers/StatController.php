<?php

namespace App\Http\Controllers;

use App\Metrika\helpers\Options;
use App\Metrika\Report;
use Exception;

class StatController extends Controller
{

    private Options $options;
    private Report $report;

    public function __construct(Options $options, Report $report)
    {
        $this->options = $options;
        $this->report = $report;
    }

    /**
     * Получаем статистику по посетителям за последние 12 месяцев
     *
     * @return string
     * @throws Exception
     */
    public function getVisits()
    {

        // получим данные за последний год:

        // год назад с первого дня текущего месяца
        $date1 = now()->modify('-12 month')->format('Y-m-01');

        // последний день предыдущего месяца
        $date2 = now()->modify('-1 month')->format('Y-m-t');


        $options = $this->options->setPreset("sources_summary")
                    ->setMetrics("ym:s:visits")
                    ->setGroup("month")
                    ->setDate1($date1)
                    ->setDate2($date2)
                    ->setId(env('METRIKA_ID'))
                    ->toArray();

        $data = $this->report->getStatByTime($options);

        $result = json_decode($data);

        // получим описание всех временных интервалов 06.2018, 07.2018 и т.д.
        $timeValueStorage = getTimeValueStorage($result->time_intervals);

        // получим значения по метрике "посетители" для всех временных интевалов: 903000 для 06.2018, 2471000 для 07.2018 и т.д.
        $metValueStorage = getMetValueStorageTotal($result->totals[0], $timeValueStorage);


        // преобразуем в читаемую дату
        foreach($metValueStorage as $index => $metValue) {
            $prettyDate = getPrettyDate($metValue[0]);
            $metValueStorage[$index][0] = $prettyDate;
        }



        return response()->json($metValueStorage, 200);
    }

    /**
     * Получаем статистику по долгосрочным интересам
     *
     */
    public function getInteres()
    {

        // получим данные за последний год:

        // год назад с первого дня текущего месяца
        $date1 = now()->modify('-12 month')->format('Y-m-01');

        // последний день предыдущего месяца
        $date2 = now()->modify('-1 month')->format('Y-m-t');

        $options = $this->options->setPreset("interests")
                        ->setMetrics("ym:s:visits")
                        ->setGroup("month")
                        ->setLimit(7)
                        ->setDate1($date1)
                        ->setDate2($date2)
                        ->setId(env('METRIKA_ID'))
                        ->toArray();

        $data = $this->report->getStatByData($options);
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
                $arrayOfColumn[] = array($dim->name,$metrics[0]);

            }


        }

        foreach($arrayOfColumn as $id=>&$data) {
            $data[1] = (int)$data[1];
        }



        return response()->json($arrayOfColumn, 200);

    }

    /**
     *  Получаем статистику по полу посетителей за последние 12 месяцев
     *
     */
    public function getGender()
    {

        // получим данные за последний год:

        // год назад с первого дня текущего месяца
        $date1 = now()->modify('-12 month')->format('Y-m-01');

        // последний день предыдущего месяца
        $date2 = now()->modify('-1 month')->format('Y-m-t');


        $options = $this->options->setDimensions("ym:s:gender")
                            ->setMetrics("ym:s:womanPercentage,ym:s:manPercentage")
                            ->setGroup("all")
                            ->setDate1($date1)
                            ->setDate2($date2)
                            ->setId(env('METRIKA_ID'))
                            ->toArray();


        $data = $this->report->getStatByTime($options);

        $result = json_decode($data);

        $woman = round($result->totals[0][0],1);
        $man = round($result->totals[1][0],1);

        $genderArray[] = array('Женский',$woman);
        $genderArray[] = array('Мужской',$man);

        return response()->json($genderArray, 200);

    }

    /**
     * Получаем статистику по возрасту посетителей за последние 12 месяцев
     *
     */
    public function getAge()
    {
        // получим данные за последний год:

        // год назад с первого дня текущего месяца
        $date1 = now()->modify('-12 month')->format('Y-m-01');

        // последний день предыдущего месяца
        $date2 = now()->modify('-1 month')->format('Y-m-t');


        $options = $this->options->setDimensions("ym:s:ageInterval")
                            ->setMetrics("ym:s:under18AgePercentage,ym:s:upTo24AgePercentage,ym:s:upTo34AgePercentage,ym:s:upTo44AgePercentage,ym:s:over44AgePercentage")
                            ->setGroup("all")
                            ->setDate1($date1)
                            ->setDate2($date2)
                            ->setId(env('METRIKA_ID'))
                            ->toArray();


        $data = $this->report->getStatByTime($options);

        $result = json_decode($data);


        // получения массив с процентам по возрастам
        foreach($result->totals as $id=>$value) {
            $valueStorage[] = round($value[0],1);
        }

        // получим все описания метрик - младше 18, 18-24 и т.д.
        $dimNameStorage = getDimNameStorage($result->data);


        // сформируем результирующий массив - метрики и проценты
        foreach($dimNameStorage as $index => $column) {
            // если всех метрик больше, чем в элементов в valueStorage
            if($index == count($valueStorage)) break;

            $ageArray[] = array($column,$valueStorage[$index]);
        }

        return response()->json($ageArray, 200);
    }
}
