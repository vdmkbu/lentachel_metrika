<?php

namespace App\Http\Controllers;

use App\AuthorReport;
use App\Metrika\helpers\Options;
use App\Metrika\Report;
use Illuminate\Http\Request;

class AuthorReportController extends Controller
{
    public function get()
    {

    }

    public function reset()
    {

    }

    public function store()
    {

        $token = env('METRIKA_TOKEN');
        $id = "28982035";

        $start = (string)\request()->input('start');
        $end = (string)\request()->input('end');

        $options = new Options();
        $options = $options->setDimensions("ym:s:date,ym:s:startURLPathFull")
            ->setMetrics("ym:s:users")
            ->setGroup("all")
            ->setLimit(100)
            ->setDate1("2019-05-01")
            ->setDate2("2019-06-01")
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


            AuthorReport::create([
                'date' => $current_date,
                'url' => $url,
                'count' => $count
            ]);

        }


    }
}
