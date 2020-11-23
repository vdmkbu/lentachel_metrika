<?php


namespace App\Services;


use Carbon\CarbonPeriod;

class SubscribeReportService
{
    public function prepareSubscribeReportData($data)
    {
        $result = json_decode($data);
        return $this->getDimensionName($result->data);
    }

    public function filterPeriod($date1, $date2)
    {

        // получим период между началом недели и текущим днем
        $period = CarbonPeriod::create($date1, $date2);
        foreach ($period as $date) {
            $filterPeriod[] = $date->format('/Y/m/d/');
        }


        // логическое ИЛИ
        $filter = null;
        if(count($filterPeriod)) {

            $filterPeriod = implode('|',$filterPeriod);

            // указываем метрику - в выборку попадают только те новости, которые вышли на текущей неделе
            $filter = " and ym:pv:URLPathFull=~'.*($filterPeriod)'";
        }

        return $filter;
    }

    // получить массив с значениями разделителей (прямые заходы, поисковые системы, etc)
    private function getDimensionName($dataObject): array
    {
        $dimNameStorage = [];

        foreach($dataObject as $dataObjectId => $dataObjectInfo)
        {

            // группировки
            $dimensions = $dataObjectInfo->dimensions;
            // имя группировки
            $dimensionsName = $dimensions[0]->name;
            //хранилище имен
            $dimNameStorage[] = $dimensionsName;

        }

        return $dimNameStorage;
    }
}
