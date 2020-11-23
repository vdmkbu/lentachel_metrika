<?php

namespace App\Http\Controllers;

use App\Metrika\helpers\Options;
use App\Metrika\Report;
use App\Services\TitleReportService;
use App\TitleReport;
use Illuminate\Support\Facades\DB;

class TitleReportController extends Controller
{

    private Options $options;
    private Report $report;
    private TitleReportService $service;

    public function __construct(Options $options, Report $report, TitleReportService $service)
    {
        $this->options = $options;
        $this->report = $report;
        $this->service = $service;
    }

    /**
     * Получим сумму по полю count для переданного title
     *
     */
    public function get()
    {

        $title = \request()->input('title');

        if(!$title)
            abort(404, "Пустой параметр title");


        return TitleReport::where('title', 'like', $title)->sum('count');
    }

    /**
     * Очищаем таблицу с отчётом
     *
     */
    public function reset()
    {
        TitleReport::truncate();
        return response(['result' => true], 200);
    }

    /**
     * Сохраним данные из отчёта по заголовкам
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store()
    {

        $start = \request()->input('start');
        $end = \request()->input('end');

        $options = $this->options->setPreset("titles")
            ->setDate1($start)
            ->setDate2($end)
            ->setId(env('METRIKA_ID'))
            ->setGroup("all")
            ->setAccuracy(1)
            ->setLimit(100000)
            ->setTitle("Адрес+страницы")
            ->toArray();


        $data = $this->report->getStatByData($options);
        $rows = $this->service->prepareTitleReportData($data);


        if (!empty($rows)) {

            DB::table('title_reports')->insert($rows);
            return response(['result' => true], 200);
        }

        return response(['result' => false], 200);
    }
}
