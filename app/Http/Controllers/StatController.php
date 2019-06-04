<?php

namespace App\Http\Controllers;

use App\Metrika\helpers\Options;
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

    public function getInteres()
    {

    }

    public function getGender()
    {

    }

    public function getAge()
    {

    }
}
