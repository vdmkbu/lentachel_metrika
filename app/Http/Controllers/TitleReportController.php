<?php

namespace App\Http\Controllers;

use App\Metrika\helpers\Options;
use App\Metrika\Report;
use App\TitleReport;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TitleReportController extends Controller
{
    /**
     * Получим сумму по полю count для переданного title
     *
     */
    public function get()
    {

        $title = \request()->input('title');

        if(!$title)
            abort(404, "Пустой параметр title");

        $sum = TitleReport::where('title', 'like', $title)->sum('count');

        return $sum;
    }

    /**
     * Очищаем таблицу с отчётом
     *
     */
    public function reset()
    {
        $result = TitleReport::truncate();
        return response(['result' => $result], 200);
    }

    /**
     * Сохраним данные из отчёта по заголовкам
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store()
    {
        $token = env('METRIKA_TOKEN');
        $id = "28982035";

        $start = \request()->input('start');
        $end = \request()->input('end');

        $options = new Options();
        $options = $options->setPreset("titles")
            ->setDate1($start)
            ->setDate2($end)
            ->setId($id)
            ->setGroup("all")
            ->setAccuracy(1)
            ->setLimit(100000)
            ->setTitle("Адрес+страницы")
            ->toArray();

        $report = new Report($token, $id);

        $data = $report->getStatByData($options);
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

        $result = false;
        if (!empty($rows)) {
            $result = TitleReport::insert($rows);
        }

        return response(['result' => $result], 200);
    }
}
