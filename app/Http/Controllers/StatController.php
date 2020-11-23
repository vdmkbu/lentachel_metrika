<?php

namespace App\Http\Controllers;

use App\Metrika\helpers\Options;
use App\Metrika\Report;
use App\Services\StatReportService;
use Exception;

class StatController extends Controller
{

    private Options $options;
    private Report $report;
    private StatReportService $service;

    public function __construct(Options $options, Report $report, StatReportService $service)
    {
        $this->options = $options;
        $this->report = $report;
        $this->service = $service;
    }

    /**
     * Получаем статистику по посетителям за последние 12 месяцев
     *
     * @return string
     * @throws Exception
     */
    public function getVisits()
    {

        // получим данные за последний год:

        // год назад с первого дня текущего месяца
        $date1 = now()->modify('-12 month')->format('Y-m-01');

        // последний день предыдущего месяца
        $date2 = now()->modify('-1 month')->format('Y-m-t');


        $options = $this->options->setPreset("sources_summary")
                    ->setMetrics("ym:s:visits")
                    ->setGroup("month")
                    ->setDate1($date1)
                    ->setDate2($date2)
                    ->setId(env('METRIKA_ID'))
                    ->toArray();

        $data = $this->report->getStatByTime($options);
        $visits = $this->service->getVisits($data);

        return response()->json($visits, 200);
    }

    /**
     * Получаем статистику по долгосрочным интересам
     *
     */
    public function getInteres()
    {

        // получим данные за последний год:

        // год назад с первого дня текущего месяца
        $date1 = now()->modify('-12 month')->format('Y-m-01');

        // последний день предыдущего месяца
        $date2 = now()->modify('-1 month')->format('Y-m-t');

        $options = $this->options->setPreset("interests")
                        ->setMetrics("ym:s:visits")
                        ->setGroup("month")
                        ->setLimit(7)
                        ->setDate1($date1)
                        ->setDate2($date2)
                        ->setId(env('METRIKA_ID'))
                        ->toArray();

        $data = $this->report->getStatByData($options);
        $interes = $this->service->getInteres($data);

        return response()->json($interes, 200);

    }

    /**
     *  Получаем статистику по полу посетителей за последние 12 месяцев
     *
     */
    public function getGender()
    {

        // получим данные за последний год:

        // год назад с первого дня текущего месяца
        $date1 = now()->modify('-12 month')->format('Y-m-01');

        // последний день предыдущего месяца
        $date2 = now()->modify('-1 month')->format('Y-m-t');


        $options = $this->options->setDimensions("ym:s:gender")
                            ->setMetrics("ym:s:womanPercentage,ym:s:manPercentage")
                            ->setGroup("all")
                            ->setDate1($date1)
                            ->setDate2($date2)
                            ->setId(env('METRIKA_ID'))
                            ->toArray();


        $data = $this->report->getStatByTime($options);
        $gender = $this->service->getGender($data);

        return response()->json($gender, 200);

    }

    /**
     * Получаем статистику по возрасту посетителей за последние 12 месяцев
     *
     */
    public function getAge()
    {
        // получим данные за последний год:

        // год назад с первого дня текущего месяца
        $date1 = now()->modify('-12 month')->format('Y-m-01');

        // последний день предыдущего месяца
        $date2 = now()->modify('-1 month')->format('Y-m-t');


        $options = $this->options->setDimensions("ym:s:ageInterval")
                            ->setMetrics("ym:s:under18AgePercentage,ym:s:upTo24AgePercentage,ym:s:upTo34AgePercentage,ym:s:upTo44AgePercentage,ym:s:over44AgePercentage")
                            ->setGroup("all")
                            ->setDate1($date1)
                            ->setDate2($date2)
                            ->setId(env('METRIKA_ID'))
                            ->toArray();


        $data = $this->report->getStatByTime($options);
        $age = $this->service->getAge($data);

        return response()->json($age, 200);
    }
}
