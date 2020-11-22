<?php

namespace App\Http\Controllers;

use App\Metrika\helpers\Options;
use App\Metrika\Report;
use App\TitleReport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TitleReportController extends Controller
{

    private Options $options;
    private Report $report;

    public function __construct(Options $options, Report $report)
    {
        $this->options = $options;
        $this->report = $report;
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
        $result = json_decode($data);

        foreach($result->data as $data=>$item) {

            $dimensions = $item->dimensions;
            $metrics = $item->metrics;


            $title = $dimensions[0]->name;
            $count = $metrics[0]; // 0 - просмотры, 1 - уники
            $current_date = Carbon::now();

            $rows[] = [
                'date' => $current_date,
                'title' => $title,
                'count' => $count
            ];


        }


        if (!empty($rows)) {

            DB::table('title_reports')->insert($rows);
            return response(['result' => true], 200);
        }

        return response(['result' => false], 200);
    }
}
