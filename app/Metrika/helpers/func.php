<?php
// получить массив с значениями total
function getMetValueStorageTotal($dataTotals, $timeValueStorage)
{
    $metValueStorage = array();

    foreach($dataTotals as $totalId=>$totalValue) {

        $metValueStorage[$totalId][0] = $timeValueStorage[$totalId];
        $metValueStorage[$totalId][] = $totalValue;
    }

    return $metValueStorage;
}

// получить массив с значениями разделителей (прямые заходы, поисковые системы, etc)
function getDimNameStorage($dataObject)
{
    $dimNameStorage = array();

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

// получить массив с датами
function getTimeValueStorage($dataTimes)
{
    $timeValueStorage = array();
    foreach($dataTimes as $dataTimesId=>$dataTimesInfo)
    {
        list($year,$month,$day) = explode('-',$dataTimesInfo[0]);
        $timeValueStorage[] = "{$month}.{$year}";
    }

    return $timeValueStorage;
}


// получить массив с значениями метрик (данные по всем резделителям)
function getMetValueStorage($dataObject, $timeValueStorage)
{
    $metValueStorage = array();

    foreach($dataObject as $dataObjectId => $dataObjectInfo) {


        // метрики
        $metrics = $dataObjectInfo->metrics;
        // хранилище данных метрики

        foreach($metrics[0] as $metId=>$metValue) {
            // TO-DO: самым первым элементом добавить дату
            $metValueStorage[$metId][0] = $timeValueStorage[$metId];
            $metValueStorage[$metId][] = $metValue;
        }

    }

    return $metValueStorage;

}


function getPrettyDate($value)
{
    $digitToWordArray = array('01'=>'Янв',
        '02'=>'Фев',
        '03'=>'Мар',
        '04'=>'Апр',
        '05'=>'Май',
        '06'=>'Июн',
        '07'=>'Июл',
        '08'=>'Авг',
        '09'=>'Сен',
        '10'=>'Окт',
        '11'=>'Ноя',
        '12'=>'Дек');

    list($month,$year) = explode('.',$value);
    $month = $digitToWordArray[$month];
    $year = $year-2000;

    return $month." ".$year;
}