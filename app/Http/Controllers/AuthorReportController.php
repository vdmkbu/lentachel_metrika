<?php

namespace App\Http\Controllers;

use App\AuthorReport;
use App\Metrika\helpers\Options;
use App\Metrika\Report;
use App\Services\AuthorReportService;
use Illuminate\Support\Facades\DB;

class AuthorReportController extends Controller
{
    private Options $options;
    private Report $report;
    private AuthorReportService $service;

    public function __construct(Options $options, Report $report, AuthorReportService $service)
    {
        $this->options = $options;
        $this->report = $report;
        $this->service = $service;
    }

    /**
     * Получим сумму по полю count для переданного URL
     *
     */
    public function get()
    {
        $url = \request()->input('url');
        if(!$url)
            abort(404, "Не передан параметр url");

        return AuthorReport::where('url', 'like', '%'.$url.'%')->sum('count');
    }

    /**
     * Очищаем таблицу с отчётом
     *
     */
    public function reset()
    {
        AuthorReport::truncate();

        return response(['result' => true], 200);
    }


    /**
     * Сохраним данные из отчёта по авторам
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store()
    {

        $start = \request()->input('start');
        $end = \request()->input('end');

        $options = $this->options->setDimensions("ym:s:startURLPathFull")
            ->setMetrics("ym:s:users")
            ->setGroup("all")
            ->setLimit(20000)
            ->setDate1($start)
            ->setDate2($end)
            ->setId(env('METRIKA_ID'))
            ->setTitle("Адрес+страницы")
            ->setAccuracy(1)
            ->toArray();

        $data = $this->report->getStatByData($options);
        $rows = $this->service->prepareAuthorReportData($data);

        if (!empty($rows)) {

            DB::table('author_reports')->insert($rows);
            return response(['result' => true], 200);
        }

        return response(['result' => false], 200);


    }
}
