<?php

namespace JosJobs\Entity;

class Category
{
    public $category_id;
    public $name;

    public $jobsTable;

    public function __construct($jobsTable)
    {
        $this->jobsTable = $jobsTable;
    }

    public function getJobs($order = null, $limit = null, $offset = null)
    {
        return $this->jobsTable->find('category_id', $this->category_id, $order, $limit, $offset);
    }

    public function getJobsCount()
    {
        return $this->jobsTable->total('category_id', $this->category_id);
    }
}
