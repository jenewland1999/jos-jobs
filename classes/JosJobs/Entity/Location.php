<?php

namespace JosJobs\Entity;

class Location
{
    public $location_id;
    public $name;

    public $jobsTable;

    public function __construct($jobsTable)
    {
        $this->jobsTable = $jobsTable;
    }

    public function getJobs($order = null, $limit = null, $offset = null)
    {
        return $this->jobsTable->find('location_id', $this->location_id, $order, $limit, $offset);
    }

    public function getJobsCount()
    {
        return $this->jobsTable->total('location_id', $this->location_id);
    }
}
