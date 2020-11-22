<?php

namespace App\Metrika\helpers;


class Options
{
    protected array $options = [];

    public function setPreset($preset)
    {
        $this->options["preset"] = $preset;
        return $this;
    }

    public function setMetrics($metrics)
    {
        $this->options["metrics"] = $metrics;
        return $this;
    }

    public function setGroup($group)
    {
        $this->options["group"] = $group;
        return $this;
    }

    public function setDate1($date1)
    {
        $this->options["date1"] = $date1;
        return $this;
    }

    public function setDate2($date2)
    {
        $this->options["date2"] = $date2;
        return $this;
    }

    public function setId($id)
    {
        $this->options["ids"] = $id;
        return $this;
    }

    public function setLimit($limit)
    {
        $this->options['limit'] = $limit;
        return $this;
    }

    public function setDimensions($dimensions)
    {
        $this->options['dimensions'] = $dimensions;
        return $this;
    }

    public function setTitle($title)
    {
        $this->options['title'] = $title;
        return $this;
    }

    public function setAccuracy($accuracy)
    {
        $this->options['accuracy'] = $accuracy;
        return $this;
    }

    public function setFilters($filters)
    {
        $this->options['filters'] = $filters;
        return $this;
    }

    public function setTopKeys($top_keys)
    {
        $this->options['top_keys'] = $top_keys;
        return $this;
    }

    public function setTable($table)
    {
        $this->options['table'] = $table;
        return $this;
    }

    public function setSort($sort)
    {
        $this->options['sort'] = $sort;
        return $this;
    }

    public function toArray()
    {
        return $this->options;
    }


}
