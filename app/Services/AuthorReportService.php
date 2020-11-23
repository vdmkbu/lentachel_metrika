<?php


namespace App\Services;


use Carbon\Carbon;

class AuthorReportService
{
    public function prepareAuthorReportData($data)
    {
        $now = Carbon::now();

        $result = json_decode($data);
        $rows = [];

        foreach($result->data as $data => $item) {

            $dimensions = $item->dimensions;
            $metrics = $item->metrics;

            $url = $dimensions[0]->name;
            $count = $metrics[0];


            $rows[] = [
                'date' => $now,
                'url' => $url,
                'count' => $count
            ];


        }

        return $rows;
    }
}
