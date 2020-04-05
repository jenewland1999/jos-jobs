<?php

namespace JosJobs\Entity;

class User
{
    public const PERM_READ_JOBS = 1;
    public const PERM_CREATE_JOBS = 2;
    public const PERM_UPDATE_JOBS = 4;
    public const PERM_ARCHIVE_JOBS = 8;
    public const PERM_DELETE_JOBS = 16;

    public const PERM_READ_CATEGORIES = 32;
    public const PERM_CREATE_CATEGORIES = 64;
    public const PERM_UPDATE_CATEGORIES = 128;
    public const PERM_DELETE_CATEGORIES = 256;

    public const PERM_READ_LOCATIONS = 512;
    public const PERM_CREATE_LOCATIONS = 1024;
    public const PERM_UPDATE_LOCATIONS = 2048;
    public const PERM_DELETE_LOCATIONS = 4096;

    public const PERM_READ_ENQUIRIES = 8192;
    public const PERM_ASSIGN_ENQUIRIES = 16384;
    public const PERM_COMPLETE_ENQUIRIES = 32768;
    public const PERM_DELETE_ENQUIRIES = 65536;

    public const PERM_READ_USERS = 131072;
    public const PERM_CREATE_USERS = 262144;
    public const PERM_UPDATE_USERS = 524288;
    public const PERM_DELETE_USERS = 1048576;
    public const PERM_PERMISSIONS_USERS = 2097152;

    public $user_id;
    public $first_name;
    public $last_name;
    public $email;
    public $password;
    public $permissions;

    public $jobsTable;

    public function __construct(\CupOfPHP\DatabaseTable $jobsTable)
    {
        $this->jobsTable = $jobsTable;
    }

    public function hasPermission($permission)
    {
        return $this->permissions & $permission;
    }

    public function addJob($job)
    {
        $job['user_id'] = $this->user_id;

        $this->jobsTable->save($job);
    }

    public function getSanitisedName()
    {
        return htmlspecialchars($this->first_name . ' ' . $this->last_name, ENT_QUOTES, 'UTF-8');
    }

    public function getJobs()
    {
        return $this->jobsTable->find('user_id', $this->user_id);
    }
}
