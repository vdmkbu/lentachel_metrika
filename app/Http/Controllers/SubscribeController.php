<?php

namespace App\Http\Controllers;

use App\Metrika\helpers\Options;
use App\Metrika\Report;
use Illuminate\Http\Request;

class SubscribeController extends Controller
{

    public function getWeekly()
    {
        $token = env('METRIKA_TOKEN');
        $id = "28982035";

        $date = new \DateTime();

        // начало недели
        $date1 = date("Y-m-d", strtotime("last Monday"));
        // сегодня
        $date2 = $date->format('Y-m-d');

        // получим период между началом недели и текущим днем
        $startDate = new \DateTime($date1);
        $endDate = new \DateTime($date2);
        $period = new \DatePeriod($startDate, new \DateInterval('P1D'), $endDate->modify('+1 day'));

        foreach ($period as $date) {
            $filterPeriodArray[] = $date->format('/Y/m/d/');
        }

        // логическое ИЛИ
        if($filterPeriodArray)
            $filterPeriod = implode('|',$filterPeriodArray);

        // указываем метрику - в выборку попадают только те новости, которые вышли на текущей неделе
        if($filterPeriod)
            $filterPeriod = " and ym:pv:URLPathFull=~'.*($filterPeriod)'";

        // Источники - сводка
        $options = new Options();
        $options = $options->setPreset("sources_summary")
            ->setDimensions("ym:pv:URLHash")
            ->setMetrics("ym:pv:pageviews")
            ->setGroup("day")
            ->setFilters("ym:pv:URLPathFull=~'.*(/news)' and ym:pv:URLPathFull=~'.*(.html)'".$filterPeriod)
            ->setSort("-ym:pv:pageviews")
            ->setDate1($date1)
            ->setDate2($date2)
            ->setId($id)
            ->setTable("hits")
            ->setTitle("Адрес+страницы")
            ->setTopKeys(30)
            ->toArray();

        $report = new Report($token, $id);

        $data = $report->getStatByTime($options);
        $result = json_decode($data);

        // получаем ссылки
        $dimNameStorage = getDimNameStorage($result->data);

        return response()->json($dimNameStorage);
    }

    public function getDaily()
    {
        $token = env('METRIKA_TOKEN');
        $id = "28982035";

        $date = new \DateTime();
        $date1 = $date->format('Y-m-d');
        $date2 = $date1;

        $filterDate = new \DateTime();
        $filterDate = $filterDate->format('Y/m/d');

        // Источники - сводка
        $options = new Options();
        $options = $options->setDate1($date1)
            ->setDate2($date2)
            ->setDimensions("ym:pv:URLHash")
            ->setMetrics("ym:pv:pageviews")
            ->setId($id)
            ->setGroup("day")
            ->setFilters("ym:pv:URLPathFull=~'.*(/news/{$filterDate})' and ym:pv:URLPathFull=~'.*(.html)'")
            ->setSort("-ym:pv:pageviews")
            ->setTable("hits")
            ->setTitle("Адрес+страницы")
            ->setTopKeys(30)
            ->toArray();

        $report = new Report($token, $id);

        $data = $report->getStatByTime($options);
        $result = json_decode($data);

        // получаем ссылки
        $dimNameStorage = getDimNameStorage($result->data);

        return response()->json($dimNameStorage);


    }
}
