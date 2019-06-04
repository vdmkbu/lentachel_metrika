<?php

namespace App\Http\Controllers;

use App\Metrika\helpers\Options;
use App\Metrika\Report;
use Illuminate\Http\Request;

class StatController extends Controller
{

    /**
     * Получаем статистику по посетителям за последние 12 месяцев
     *
     * @return string
     */
    public function getVisits()
    {
        $token = env('METRIKA_TOKEN');
        $id = "28982035";


        // TODO: готовим даты в отдельном классе или хелпере
        $now = new \DateTime();
        $nowMonth = $now->format('m');
        $oneYearAgo = $now->modify('-12 month');
        $oneYearAgoString = $oneYearAgo->format('Y-m-d');
        list($oneYearAgoY,$oneYearAgoM, $oneYearAgoD) = explode('-',$oneYearAgoString);
        $oneYearAgo = "{$oneYearAgoY}-{$oneYearAgoM}-01";
        $date1 = $oneYearAgo;

        // end - dont show current month, use flag "t"
        $date2 = new \DateTime();
        $date2 = $date2->modify('-1 month');
        $date2 = $date2->format('Y-m-t');



        $options = new Options();
        $options = $options->setPreset("sources_summary")
                    ->setMetrics("ym:s:visits")
                    ->setGroup("month")
                    ->setDate1($date1)
                    ->setDate2($date2)
                    ->setId($id)
                    ->toArray();


        $report = new Report($token, $id);
        $data = $report->getStatByTime($options);

        $result = json_decode($data);

        $timeValueStorage = getTimeValueStorage($result->time_intervals);
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
        $token = env('METRIKA_TOKEN');
        $id = "28982035";

        $now = new \DateTime();
        $nowMonth = $now->format('m');
        $oneYearAgo = $now->modify('-12 month');
        $oneYearAgoString = $oneYearAgo->format('Y-m-d');
        list($oneYearAgoY,$oneYearAgoM,$oneYearAgoD) = explode('-',$oneYearAgoString);
        $oneYearAgo = "{$oneYearAgoY}-{$oneYearAgoM}-01";

        //start
        $date1 = $oneYearAgo;

        //end - dont show current month, use flag t
        $date2 = new \DateTime();
        $date2 = $date2->modify('-1month');
        $date2 = $date2->format('Y-m-t');

        $options = new Options();
        $options = $options->setPreset("interests")
                        ->setMetrics("ym:s:visits")
                        ->setGroup("month")
                        ->setLimit(7)
                        ->setDate1($date1)
                        ->setDate2($date2)
                        ->setId($id)
                        ->toArray();

        $report = new Report($token, $id);
        $data = $report->getStatByData($options);
        $result = json_decode($data);

        // общее количество
        $total = 0;

        foreach($result->data as $id => $content) {
            $dimensions = $content->dimensions;
            $metrics = $content->metrics;

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
        $token = env('METRIKA_TOKEN');
        $id = "28982035";

        $now = new \DateTime();
        $nowMonth = $now->format('m');
        $oneYearAgo = $now->modify('-12 month');
        $oneYearAgoString = $oneYearAgo->format('Y-m-d');
        list($oneYearAgoY,$oneYearAgoM, $oneYearAgoD) = explode('-',$oneYearAgoString);
        $oneYearAgo = "{$oneYearAgoY}-{$oneYearAgoM}-01";
        // start
        $date1 = $oneYearAgo;

        // end - dont show current month, use flag "t"
        $date2 = new \DateTime();
        $date2 = $date2->modify('-1 month');
        $date2 = $date2->format('Y-m-t');

        $options = new Options();
        $options = $options->setDimensions("ym:s:gender")
                            ->setMetrics("ym:s:womanPercentage,ym:s:manPercentage")
                            ->setGroup("all")
                            ->setDate1($date1)
                            ->setDate2($date2)
                            ->setId($id)
                            ->toArray();

        $report = new Report($token, $id);
        $data = $report->getStatByTime($options);

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
        $token = env('METRIKA_TOKEN');
        $id = "28982035";

        $now = new \DateTime();
        $nowMonth = $now->format('m');
        $oneYearAgo = $now->modify('-12 month');
        $oneYearAgoString = $oneYearAgo->format('Y-m-d');
        list($oneYearAgoY,$oneYearAgoM, $oneYearAgoD) = explode('-',$oneYearAgoString);
        $oneYearAgo = "{$oneYearAgoY}-{$oneYearAgoM}-01";
        // start
        $date1 = $oneYearAgo;

        // end - dont show current month, use flag "t"
        $date2 = new \DateTime();
        $date2 = $date2->modify('-1 month');
        $date2 = $date2->format('Y-m-t');

        $options = new Options();
        $options = $options->setDimensions("ym:s:ageInterval")
                            ->setMetrics("ym:s:under18AgePercentage,ym:s:upTo24AgePercentage,ym:s:upTo34AgePercentage,ym:s:upTo44AgePercentage,ym:s:over44AgePercentage")
                            ->setGroup("all")
                            ->setDate1($date1)
                            ->setDate2($date2)
                            ->setId($id)
                            ->toArray();

        $report = new Report($token, $id);
        $data = $report->getStatByTime($options);

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
