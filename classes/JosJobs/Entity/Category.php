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

    public function getJobs()
    {
        return $this->jobsTable->find('category_id', $this->category_id);
    }
}
