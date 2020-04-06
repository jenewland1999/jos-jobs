<?php

namespace JosJobs\Controllers;

use CupOfPHP\Authentication;
use CupOfPHP\DatabaseTable;

class Job
{
    private $authentication;
    private $applicationsTable;
    private $categoriesTable;
    private $jobsTable;
    private $locationsTable;

    public function __construct(
        Authentication $authentication,
        DatabaseTable $applicationsTable,
        DatabaseTable $categoriesTable,
        DatabaseTable $jobsTable,
        DatabaseTable $locationsTable,
        DatabaseTable $usersTable
    ) {
        $this->authentication = $authentication;
        $this->applicationsTable = $applicationsTable;
        $this->categoriesTable = $categoriesTable;
        $this->jobsTable = $jobsTable;
        $this->locationsTable = $locationsTable;
        $this->usersTable = $usersTable;
    }

    public function read()
    {
        // Retrieve the current authenticated user
        $authUser = $this->authentication->getUser();

        // Retrieve a list of categories
        $categories = $this->categoriesTable->findAll();

        // Retrieve a list of locations
        $locations = $this->locationsTable->findAll();

        // Pagination - Get the page number
        $page = $_GET['page'] ?? 1;

        // Pagination - Get the offset (For DBTable class method)
        $offset = ($page - 1) * 10;

        if (isset($_GET['category']) && isset($_GET['location'])) {
            // Define the page heading
            $heading = sprintf(
                '%s Jobs in %s',
                $this->categoriesTable->findById($_GET['category'])->name,
                $this->locationsTable->findById($_GET['location'])->name
            );

            // Define the search criteria
            $criteria = [
                [
                    'field' => 'category_id',
                    'operator' => '=',
                    'value' => $_GET['category'],
                    'type' => 'AND'
                ],
                [
                    'field' => 'location_id',
                    'operator' => '=',
                    'value' => $_GET['location'],
                    'type' => 'AND'
                ],
                [
                    'field' => 'closing_date',
                    'operator' => '>',
                    'value' => (new \DateTime())->format('Y-m-d H:i:s'),
                    'type' => 'AND'
                ],
                [
                    'field' => 'is_archived',
                    'operator' => '=',
                    'value' => 'FALSE'
                ]
            ];
        } elseif (isset($_GET['category'])) {
            // Define the page heading
            $heading = sprintf(
                '%s Jobs',
                $this->categoriesTable->findById($_GET['category'])->name
            );

            // Define the search criteria
            $criteria = [
                [
                    'field' => 'category_id',
                    'operator' => '=',
                    'value' => $_GET['category'],
                    'type' => 'AND'
                ],
                [
                    'field' => 'closing_date',
                    'operator' => '>',
                    'value' => (new \DateTime())->format('Y-m-d H:i:s'),
                    'type' => 'AND'
                ],
                [
                    'field' => 'is_archived',
                    'operator' => '=',
                    'value' => 'FALSE'
                ]
            ];
        } elseif (isset($_GET['location'])) {
            // Define the page heading
            $heading = sprintf(
                'Jobs in %s',
                $this->locationsTable->findById($_GET['location'])->name
            );

            // Define the search criteria
            $criteria = [
                [
                    'field' => 'location_id',
                    'operator' => '=',
                    'value' => $_GET['location'],
                    'type' => 'AND'
                ],
                [
                    'field' => 'closing_date',
                    'operator' => '>',
                    'value' => (new \DateTime())->format('Y-m-d H:i:s'),
                    'type' => 'AND'
                ],
                [
                    'field' => 'is_archived',
                    'operator' => '=',
                    'value' => 'FALSE'
                ]
            ];
        } else {
            // Define the page heading
            $heading = 'All Jobs';

            // Define the search criteria
            $criteria = [
                [
                    'field' => 'closing_date',
                    'operator' => '>',
                    'value' => (new \DateTime())->format('Y-m-d H:i:s'),
                    'type' => 'AND'
                ],
                [
                    'field' => 'is_archived',
                    'operator' => '=',
                    'value' => 'FALSE'
                ]
            ];
        }

        // Retrieve a list of jobs matching the criteria defined above
        $jobs = $this->jobsTable->findComplex($criteria, 'closing_date', 10, $offset);

        // Retrieve the number of jobs matching the criteria defined above (Pagination)
        $totalJobs = $this->jobsTable->totalComplex($criteria);

        return [
            'template' => '/jobs/index.html.php',
            'title' => 'Jobs',
            'variables' => [
                'authUser' => $authUser,
                'categories' => $categories,
                'categoryId' => $_GET['category'] ?? null,
                'currentPage' => $page,
                'heading' => $heading,
                'jobs' => $jobs,
                'locations' => $locations,
                'locationId' => $_GET['location'] ?? null,
                'totalJobs' => $totalJobs
            ]
        ];
    }

    public function readPrivileged()
    {
        // Get the current authenticated user
        $authUser = $this->authentication->getUser();

        // Get a list of categories
        $categories = $this->categoriesTable->findAll();

        // Get a list of locations
        $locations = $this->locationsTable->findAll();

        // Pagination - Get the page number
        $page = $_GET['page'] ?? 1;

        // Pagination - Get the offset (For DBTable class method)
        $offset = ($page - 1) * 10;

        if (isset($_GET['category']) && isset($_GET['location'])) {
            $jobs = $this->jobsTable->findComplex(
                [
                    [
                        'field' => 'category_id',
                        'operator' => '=',
                        'value' => $_GET['category'],
                        'type' => 'AND'
                    ],
                    [
                        'field' => 'location_id',
                        'operator' => '=',
                        'value' => $_GET['location']
                    ]
                ],
                'closing_date',
                10,
                $offset
            );
            $totalJobs = $this->jobsTable->totalComplex(
                [
                    [
                        'field' => 'category_id',
                        'operator' => '=',
                        'value' => $_GET['category'],
                        'type' => 'AND'
                    ],
                    [
                        'field' => 'location_id',
                        'operator' => '=',
                        'value' => $_GET['location']
                    ]
                ]
            );
        } elseif (isset($_GET['category'])) {
            $category = $this->categoriesTable->findById($_GET['category']);
            $jobs = $category->getJobs('closing_date', 10, $offset);
            $totalJobs = $category->getJobsCount();
        } elseif (isset($_GET['location'])) {
            $location = $this->locationsTable->findById($_GET['location']);
            $jobs = $location->getJobs('closing_date', 10, $offset);
            $totalJobs = $location->getJobsCount();
        } else {
            $jobs = $this->jobsTable->findAll('closing_date', 10, $offset);
            $totalJobs = $this->jobsTable->total();
        }

        return [
            'template' => '/admin/jobs/index.html.php',
            'title' => 'Admin - Jobs',
            'variables' => [
                'authUser' => $authUser,
                'categories' => $categories,
                'categoryId' => $_GET['category'] ?? null,
                'currentPage' => $page,
                'jobs' => $jobs,
                'locationId' => $_GET['location'] ?? null,
                'locations' => $locations,
                'totalJobs' => $totalJobs
            ]
        ];
    }

    public function update()
    {
        // Fetch a list of categories
        $categories = $this->categoriesTable->findAll();

        // Fetch a list of locations
        $locations = $this->locationsTable->findAll();

        if (isset($_GET['id'])) {
            $job = $this->jobsTable->findById($_GET['id']);
            $title = 'Admin - Jobs - Update';
        } else {
            $title = 'Admin - Jobs - Create';
        }

        return [
            'template' => '/admin/jobs/update.html.php',
            'title' => $title,
            'variables' => [
                'authUser' => $this->authentication->getUser(),
                'categories' => $categories,
                'job' => $job ?? null,
                'locations' => $locations
            ]
        ];
    }

    public function saveUpdate()
    {
        // Tidy up the form data
        $_POST['job']['title'] = trim($_POST['job']['title']);
        $_POST['job']['description'] = trim($_POST['job']['description']);
        $_POST['job']['salary'] = trim($_POST['job']['salary']);

        // Get the current authenticated user
        $authUser = $this->authentication->getUser();

        // Create an object to store the new job in
        $job = new \JosJobs\Entity\Job(
            $this->applicationsTable,
            $this->categoriesTable,
            $this->locationsTable,
            $this->usersTable
        );

        // Populate the fields of the object using the form data from $_POST
        foreach ($_POST['job'] as $key => $value) {
            $job->$key = $value;
        }

        // Declare an array to store potential form validation errors
        $errors = [];

        if (empty($job->title)) {
            $errors[] = 'Title cannot be blank';
        } elseif (strlen($job->title) >= 255) {
            $errors[] = 'Title exceeds max length of 255 characters';
        }

        if (empty($job->description)) {
            $errors[] = 'Description cannot be blank';
        } elseif (strlen($job->description) >= 8191) {
            $errors[] = 'Description exceeds max length of 8191 characters';
        }

        // TODO: Potentially remove to permit non-disclosure of salary - likely
        // TODO: Potentially overhaul with low, high and frequency fields - unlikely
        if (empty($job->salary)) {
            $errors[] = 'Salary cannot be blank';
        } elseif (strlen($job->salary) >= 255) {
            $errors[] = 'Salary exceeds max length of 255 characters';
        }

        if (!$job->category_id) {
            $errors[] = 'A category must be selected';
        }

        if (!$job->location_id) {
            $errors[] = 'A location must be selected';
        }

        if (empty($job->closing_date)) {
            $errors[] = 'Closing date cannot be blank';
        } else {
            // Some of the following code was derived from the following
            // Stack Overflow post by Amal Murali
            // https://stackoverflow.com/questions/19271381/
            // Accessed 30 Mar 2020
            $closingDate = \DateTime::createFromFormat('Y-m-d', $job->closing_date);
            $now = new \DateTime();
            $nowP1Y = (new \DateTime())->modify('+1 year');

            // check date is valid format
            if (!$closingDate && $closingDate->format('Y-m-d') !== $job->closing_date) {
                $errors[] = 'Invalid closing date and/or format';
            } elseif ($closingDate <= $now) { // check date is after today
                $errors[] = 'Closing date must be after today';
            } elseif ($closingDate >= $nowP1Y) { // check date is before 1 year into the future
                $errors[] = 'Closing date must be less than a year in advance';
            }
        }

        // If no errors were detected in form validation stage proceed to add record to db
        if (empty($errors)) {
            // Add the record to the database
            $authUser->addJob($_POST['job']);

            // Redirect the user to the jobs dashboard page
            header('location: /admin/jobs/');
        } else {
            // Fetch a list of categories
            $categories = $this->categoriesTable->findAll();

            // Fetch a list of locations
            $locations = $this->locationsTable->findAll();

            $title = 'Admin - Jobs - ';
            $title .= isset($_GET['id']) ? 'Update' : 'Create';

            return [
                'template' => '/admin/jobs/update.html.php',
                'title' => $title,
                'variables' => [
                    'authUser' => $authUser,
                    'categories' => $categories,
                    'errors' => $errors,
                    'job' => $job ?? null,
                    'locations' => $locations
                ]
            ];
        }
    }

    public function archive()
    {
        // Get the current authenticated user
        $authUser = $this->authentication->getUser();

        // Get the job object to be archived
        $job = $this->jobsTable->findById($_POST['job_id']);

        // If the authenticated user doesn't own the job being deleted and
        // isn't permitted to delete jobs then prevent delete action
        if (
            $job->user_id !== $authUser->user_id &&
            !$authUser->hasPermission(\JosJobs\Entity\User::PERM_DELETE_JOBS)
        ) {
            return;
        }

        // Perform the archive/un-archive operation on the job
        // ? Sets the is_archived field to the opposite of what it currently is
        $this->jobsTable->update([
            'job_id' => $_POST['job_id'],
            'is_archived' => !$job->is_archived
        ]);

        // Redirect the user to jobs page
        header('location: /admin/jobs/');
    }

    public function delete()
    {
        // Get the current authenticated user
        $authUser = $this->authentication->getUser();

        // Get the job object selected for deletion
        $job = $this->jobsTable->findById($_POST['job_id']);

        // If the authenticated user doesn't own the job being deleted and
        // isn't permitted to delete jobs then prevent delete action
        if (
            $job->user_id !== $authUser->user_id &&
            !$authUser->hasPermission(\JosJobs\Entity\User::PERM_DELETE_JOBS)
        ) {
            return;
        }

        // Perform the deletion of the job
        $this->jobsTable->deleteById($_POST['job_id']);

        // Redirect the user to jobs page
        header('location: /admin/jobs/');
    }

    public function apply()
    {
        // If a job isn't specified return them to the list of jobs
        // TODO: Pass an error message to this page to explain the issue.
        if (!isset($_GET['id'])) {
            header('location: /jobs/');
        }

        // Retrieve the authenticated user
        $authUser = $this->authentication->getUser();

        // Retrieve the specified job
        $job = $this->jobsTable->findById($_GET['id']);

        return [
            'template' => '/jobs/apply.html.php',
            'title' => 'Jobs - Apply',
            'variables' => [
                'authUser' => $authUser,
                'job' => $job ?? null
            ]
        ];
    }

    public function saveApply()
    {
        // Store the application for easier access
        $application = $_POST['application'];

        // Tidy up the form data
        $application['name'] = trim($application['name']);
        $application['email'] = trim($application['email']);
        $application['details'] = trim($application['details']);

        // Declare an array to store potential form validation errors
        $errors = [];

        // Validate name field
        if (empty($application['name'])) {
            $errors[] = 'Name cannot be blank';
        } elseif (strlen($application['name']) > 255) {
            $errors[] = 'Name exceeds max length of 255 characters';
        }

        // Validate email field
        if (empty($application['email'])) {
            $errors[] = 'Email cannot be blank';
        } elseif (strlen($application['email']) > 255) {
            $errors[] = 'Email exceeds max length of 255 characters';
        } elseif (!filter_var($application['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email must be a valid email address';
        }

        // Validate CV file upload
        if (empty($_FILES)) {
            $errors[] = 'CV not selected';
        } else {
            // The following code is derived from an article on Cloudinary
            // Blog written by Prosper Otemuyiwa on 15 Feb 2017
            // https://cloudinary.com/blog/file_upload_with_php
            // Accessed 01 Mar 2020

            // Check the CVs directory exists and if not, create it
            $currentDir = getcwd();
            $cvUploadsDir = '/uploads/cvs/';
            if (!file_exists($currentDir . $cvUploadsDir)) {
                mkdir($currentDir . $cvUploadsDir, 0755, true);
            }

            // Define a list of valid CV file extensions
            $validFileExtensions = ['doc', 'docx', 'pdf', 'rtf'];

            // Retrieve info about the cv from the $_FILES superglobal
            $fileName = $_FILES['cv']['name'];
            $fileParts = explode('.', $fileName);
            $fileExt = strtolower(end($fileParts));
            $fileSize = $_FILES['cv']['size'];
            $fileType = $_FILES['cv']['type'];
            $fileTmpName = $_FILES['cv']['tmp_name'];

            // Generate a unique ID for the cv (prevents other users finding them easily)
            $newFileName = uniqid('cv_') . '.' . $fileExt;

            // Create the upload path to move the file to
            $uploadPath = $currentDir . $cvUploadsDir . $newFileName;

            // File upload validation - valid file extension
            if (!in_array($fileExt, $validFileExtensions)) {
                $errors[] = 'This file extension is not allowed. Please upload a DOC, DOCX, PDF or RTF document.';
            }

            // File upload validation - valid file size (<= 5MB)
            if ($fileSize > 5000000) {
                $errors[] = 'This file exceeds the upload limit of 5MB. Please upload a smaller file.';
            }
        }

        // If no errors were detected submit the form and upload the CV and add
        // the record to the DB.
        if (empty($errors)) {
            $isUploadSuccessful = move_uploaded_file($fileTmpName, $uploadPath);

            if ($isUploadSuccessful) {
                $_POST['application']['cv'] = $newFileName;
                $this->applicationsTable->insert($_POST['application']);

                header('location: /jobs');
            }
        } else {
            return [
                'template' => '/jobs/apply.html.php',
                'title' => 'Jobs - Apply',
                'variables' => [
                    'application' => $application ?? null,
                    'errors' => $errors,
                    'job' => $this->jobsTable->findById($_GET['id'])
                ]
            ];
        }
    }

    public function applications()
    {
        // If a job isn't specified return them to the list of jobs
        // TODO: Pass an error message to this page to explain the issue.
        if (!isset($_GET['id'])) {
            header('location: /admin/jobs/');
        }

        // Retrieve the authenticated user
        $authUser = $this->authentication->getUser();

        // Retrieve the specified job
        $job = $this->jobsTable->findById($_GET['id']);

        return [
            'template' => '/admin/jobs/applications/index.html.php',
            'title' => 'Admin - Jobs - Applications',
            'variables' => [
                'authUser' => $authUser,
                'job' => $job ?? null
            ]
        ];
    }
}
