<?php


namespace App\Services;


use Carbon\Carbon;

class TitleReportService
{
    public function prepareTitleReportData($data)
    {
        $result = json_decode($data);

        $now = Carbon::now();
        $rows = [];
        foreach($result->data as $data=>$item) {

            $dimensions = $item->dimensions;
            $metrics = $item->metrics;

            $title = $dimensions[0]->name;
            $count = $metrics[0]; // 0 - просмотры, 1 - уники

            $rows[] = [
                'date' => $now,
                'title' => $title,
                'count' => $count
            ];


        }

        return $rows;
    }
}
