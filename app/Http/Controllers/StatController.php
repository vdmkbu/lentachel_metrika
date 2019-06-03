<?php

namespace App\Http\Controllers;

use App\Metrika\Report;
use Illuminate\Http\Request;

class StatController extends Controller
{

    /**
     * @return string
     */
    public function getVisits()
    {
        $token = env('METRIKA_TOKEN');
        $id = "28982035";


        // TODO: готовим даты в отдельном классе или хелпере
        $now = new \DateTime();
        $nowMonth = $now->format('m');
        $halfYearAgo = $now->modify('-12 month');
        $halfYearAgoString = $halfYearAgo->format('Y-m-d');
        list($halfYearAgoY,$halfYearAgoM, $halfYearAgoD) = explode('-',$halfYearAgoString);
        $halfYearAgo = "{$halfYearAgoY}-{$halfYearAgoM}-01";
        // start
        $date1 = $halfYearAgo;

        // end - dont show current month, use flag "t"
        $date2 = new \DateTime();
        $date2 = $date2->modify('-1 month');
        $date2 = $date2->format('Y-m-t');


        // TODO: формируем options динамически - добавить класс
        $preset = "sources_summary";
        $metrics = "ym:s:visits";
        //$metrics = "ym:s:users"; // посетители
        $group = "month";

        $options = array(
            'preset'=>$preset,
            'date1'=>$date1,
            'date2'=>$date2,
            'metrics'=>$metrics,
            'ids'=>$id,
            'group'=>$group
        );



        $report = new Report($token, $id);

        $data = $report->getStatByTime($options);

        $result = json_decode($data);

        $timeVlaueStorage = getTimeValueStorage($result->time_intervals);
        $metValueStorage = getMetValueStorageTotal($result->totals[0], $timeVlaueStorage);

        // преобразуем в читаемую дату
        foreach($metValueStorage as $index => $metValue) {
            $prettyDate = getPrettyDate($metValue[0]);
            $metValueStorage[$index][0] = $prettyDate;
        }

        $metValueJson = json_encode($metValueStorage, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);


        return $metValueJson;
    }
}
