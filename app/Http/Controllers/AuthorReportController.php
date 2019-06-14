<?php

namespace App\Http\Controllers;

use App\AuthorReport;
use App\Metrika\helpers\Options;
use App\Metrika\Report;
use Illuminate\Http\Request;

class AuthorReportController extends Controller
{
    /**
     * Получим сумму по полю count для переданного URL
     *
     */
    public function get()
    {
        $url = \request()->input('url');
        if(!$url)
            abort(404, "Не передан параметр url");

        $sum = AuthorReport::where('url', 'like', '%'.$url.'%')->sum('count');

        return $sum;
    }

    /**
     * Очищаем таблицу с отчётом
     *
     */
    public function reset()
    {
        $result = AuthorReport::truncate();

        return response(['result' => $result], 200);
    }


    /**
     * Сохраним данные из отчёта по авторам
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
        $options = $options->setDimensions("ym:s:date,ym:s:startURLPathFull")
            ->setMetrics("ym:s:users")
            ->setGroup("all")
            ->setLimit(10000)
            ->setDate1($start)
            ->setDate2($end)
            ->setId($id)
            ->setTitle("Адрес+страницы")
            ->setAccuracy(1)
            ->toArray();



        $report = new Report($token, $id);
        $data = $report->getStatByData($options);
        $result = json_decode($data);


        foreach($result->data as $data => $item) {

            $dimensions = $item->dimensions;
            $metrics = $item->metrics;


            $current_date = $dimensions[0]->name;
            $url = $dimensions[1]->name;
            $count = $metrics[0];


            $rows[] = [
                    'date' => $current_date,
                    'url' => $url,
                    'count' => $count
                ];


        }


        $result = false;
        if (!empty($rows)) {
            $result = AuthorReport::insert($rows);
        }

        return response(['result' => $result], 200);

    }
}
