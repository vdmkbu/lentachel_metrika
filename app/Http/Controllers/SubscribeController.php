<?php

namespace App\Http\Controllers;

use App\Metrika\helpers\Options;
use App\Metrika\Report;
use App\Services\SubscribeReportService;
use Carbon\Carbon;

class SubscribeController extends Controller
{
    private Options $options;
    private Report $report;
    private SubscribeReportService $service;

    public function __construct(Options $options, Report $report, SubscribeReportService $service)
    {
        $this->options = $options;
        $this->report = $report;
        $this->service = $service;
    }

    public function getWeekly()
    {

        // начало недели
        $date1 = Carbon::parse(strtotime("last Monday"))->format('Y-m-d');

        // сегодня
        $date2 = now()->format('Y-m-d');

        $filter = $this->service->filterPeriod($date1, $date2);

        // Источники - сводка
        $options = $this->options->setPreset("sources_summary")
            ->setDimensions("ym:pv:URLHash")
            ->setMetrics("ym:pv:pageviews")
            ->setGroup("day")
            ->setFilters("ym:pv:URLPathFull=~'.*(/news)' and ym:pv:URLPathFull=~'.*(.html)'".$filter)
            ->setSort("-ym:pv:pageviews")
            ->setDate1($date1)
            ->setDate2($date2)
            ->setId(env('METRIKA_ID'))
            ->setTable("hits")
            ->setTitle("Адрес+страницы")
            ->setTopKeys(30)
            ->toArray();

        $data = $this->report->getStatByTime($options);
        $weekly = $this->service->prepareSubscribeReportData($data);

        return response()->json($weekly);
    }

    public function getDaily()
    {

        $date1 = $date2 = now()->format('Y-m-d');
        $filter = now()->format('Y/m/d');

        // Источники - сводка
        $options = $this->options->setDate1($date1)
            ->setDate2($date2)
            ->setDimensions("ym:pv:URLHash")
            ->setMetrics("ym:pv:pageviews")
            ->setId(env('METRIKA_ID'))
            ->setGroup("day")
            ->setFilters("ym:pv:URLPathFull=~'.*(/news/{$filter})' and ym:pv:URLPathFull=~'.*(.html)'")
            ->setSort("-ym:pv:pageviews")
            ->setTable("hits")
            ->setTitle("Адрес+страницы")
            ->setTopKeys(30)
            ->toArray();

        $data = $this->report->getStatByTime($options);
        $daily = $this->service->prepareSubscribeReportData($data);

        return response()->json($daily);
    }
}
