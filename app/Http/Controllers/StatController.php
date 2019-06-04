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

    public function getGender()
    {

    }

    public function getAge()
    {

    }
}
