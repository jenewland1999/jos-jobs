<?php

namespace JosJobs\Entity;

class Enquiry
{
    public $enquiry_id;
    public $name;
    public $email;
    public $tel_no;
    public $user_id;
    public $is_complete;

    public $usersTable;

    private $user;

    public function __construct($usersTable)
    {
        $this->usersTable = $usersTable;
    }

    public function getUser()
    {
        if (empty($this->user)) {
            $this->user = $this->usersTable->findById($this->user_id);
        }

        return $this->user;
    }
}
