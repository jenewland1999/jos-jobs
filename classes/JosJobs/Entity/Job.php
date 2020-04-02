<?php

namespace JosJobs\Entity;

class Job
{
    public $job_id;
    public $title;
    public $description;
    public $salary;
    public $closing_date;
    public $is_archived;
    public $category_id;
    public $location_id;
    public $user_id;

    public $applicationsTable;
    public $categoriesTable;
    public $locationsTable;
    public $usersTable;

    private $category;
    private $location;
    private $user;

    public function __construct(
        \CupOfPHP\DatabaseTable $applicationsTable,
        \CupOfPHP\DatabaseTable $categoriesTable,
        \CupOfPHP\DatabaseTable $locationsTable,
        \CupOfPHP\DatabaseTable $usersTable
    ) {
        $this->applicationsTable = $applicationsTable;
        $this->categoriesTable = $categoriesTable;
        $this->locationsTable = $locationsTable;
        $this->usersTable = $usersTable;
    }

    public function getApplications()
    {
        return $this->applicationsTable->find('job_id', $this->job_id);
    }

    public function getApplicationCount()
    {
        return $this->applicationsTable->total('job_id', $this->job_id);
    }

    public function getCategory()
    {
        if (empty($this->category)) {
            $this->category = $this->categoriesTable->findById($this->category_id);
        }

        return $this->category;
    }

    public function getLocation()
    {
        if (empty($this->location)) {
            $this->location = $this->locationsTable->findById($this->location_id);
        }

        return $this->location;
    }

    public function getUser()
    {
        if (empty($this->user)) {
            $this->user = $this->usersTable->findById($this->user_id);
        }

        return $this->user;
    }
}
