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

    public function getJobs()
    {
        return $this->jobsTable->find('location_id', $this->location_id);
    }
}
