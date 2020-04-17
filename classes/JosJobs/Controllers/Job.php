<?php

namespace JosJobs\Controllers;

use CupOfPHP\Authentication;
use CupOfPHP\DatabaseTable;
use CupOfPHP\FileUpload;

class Job
{
    private $authentication;
    private $applicationsTable;
    private $categoriesTable;
    private $jobsTable;
    private $locationsTable;
    private $usersTable;
    private $get;
    private $post;
    private $fileUpload;

    public function __construct(
        Authentication $authentication,
        DatabaseTable $applicationsTable,
        DatabaseTable $categoriesTable,
        DatabaseTable $jobsTable,
        DatabaseTable $locationsTable,
        DatabaseTable $usersTable,
        array $get,
        array $post,
        FileUpload $fileUpload
    ) {
        $this->authentication = $authentication;
        $this->applicationsTable = $applicationsTable;
        $this->categoriesTable = $categoriesTable;
        $this->jobsTable = $jobsTable;
        $this->locationsTable = $locationsTable;
        $this->usersTable = $usersTable;
        $this->get = $get;
        $this->post = $post;
        $this->fileUpload = $fileUpload;
    }

    public function read()
    {
        // Pagination - Get the page number
        $page = $this->get['page'] ?? 1;

        // Pagination - Get the offset (For DBTable class method)
        $offset = ($page - 1) * 10;

        if (isset($this->get['category']) && isset($this->get['location'])) {
            // Define the page heading
            $heading = sprintf(
                '%s Jobs in %s',
                $this->categoriesTable->findById($this->get['category'])->name,
                $this->locationsTable->findById($this->get['location'])->name
            );

            // Define the search criteria
            $criteria = [
                [
                    'field' => 'category_id',
                    'operator' => '=',
                    'value' => $this->get['category'],
                    'type' => 'AND'
                ],
                [
                    'field' => 'location_id',
                    'operator' => '=',
                    'value' => $this->get['location'],
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
        } elseif (isset($this->get['category'])) {
            // Define the page heading
            $heading = sprintf(
                '%s Jobs',
                $this->categoriesTable->findById($this->get['category'])->name
            );

            // Define the search criteria
            $criteria = [
                [
                    'field' => 'category_id',
                    'operator' => '=',
                    'value' => $this->get['category'],
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
        } elseif (isset($this->get['location'])) {
            // Define the page heading
            $heading = sprintf(
                'Jobs in %s',
                $this->locationsTable->findById($this->get['location'])->name
            );

            // Define the search criteria
            $criteria = [
                [
                    'field' => 'location_id',
                    'operator' => '=',
                    'value' => $this->get['location'],
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
                'authUser' => $this->authentication->getUser(),
                'categories' => $this->categoriesTable->findAll(),
                'categoryId' => $this->get['category'] ?? null,
                'currentPage' => $page,
                'heading' => $heading,
                'jobs' => $jobs,
                'locations' => $this->locationsTable->findAll(),
                'locationId' => $this->get['location'] ?? null,
                'totalJobs' => $totalJobs
            ]
        ];
    }

    public function readPrivileged()
    {
        // Retrieve the authenticated user
        $authUser = $this->authentication->getUser();

        if ($authUser->hasPermission(\JosJobs\Entity\User::PERM_READ_ANY_JOBS)) {
            $userRestriction = null;
        } else {
            $userRestriction = [
                'field' => 'user_id',
                'operator' => '=',
                'value' => $authUser->user_id,
                'type' => 'AND'
            ];
        }

        // Pagination - Get the page number
        $page = $this->get['page'] ?? 1;

        // Pagination - Get the offset (For DBTable class method)
        $offset = ($page - 1) * 10;

        if (isset($this->get['category']) && isset($this->get['location'])) {
            $jobs = $this->jobsTable->findComplex(
                [
                    $userRestriction,
                    [
                        'field' => 'category_id',
                        'operator' => '=',
                        'value' => $this->get['category'],
                        'type' => 'AND'
                    ],
                    [
                        'field' => 'location_id',
                        'operator' => '=',
                        'value' => $this->get['location']
                    ]
                ],
                'closing_date',
                10,
                $offset
            );
            $totalJobs = $this->jobsTable->totalComplex(
                [
                    $userRestriction,
                    [
                        'field' => 'category_id',
                        'operator' => '=',
                        'value' => $this->get['category'],
                        'type' => 'AND'
                    ],
                    [
                        'field' => 'location_id',
                        'operator' => '=',
                        'value' => $this->get['location']
                    ]
                ]
            );
        } elseif (isset($this->get['category'])) {
            $jobs = $this->jobsTable->findComplex(
                [
                    $userRestriction,
                    [
                        'field' => 'category_id',
                        'operator' => '=',
                        'value' => $this->get['category']
                    ]
                ],
                'closing_date',
                10,
                $offset
            );
            $totalJobs = $this->jobsTable->totalComplex(
                [
                    $userRestriction,
                    [
                        'field' => 'category_id',
                        'operator' => '=',
                        'value' => $this->get['category']
                    ]
                ]
            );
        } elseif (isset($this->get['location'])) {
            $jobs = $this->jobsTable->findComplex(
                [
                    $userRestriction,
                    [
                        'field' => 'location_id',
                        'operator' => '=',
                        'value' => $this->get['location']
                    ]
                ],
                'closing_date',
                10,
                $offset
            );
            $totalJobs = $this->jobsTable->totalComplex(
                [
                    $userRestriction,
                    [
                        'field' => 'location_id',
                        'operator' => '=',
                        'value' => $this->get['location']
                    ]
                ]
            );
        } else {
            if ($authUser->hasPermission(\JosJobs\Entity\User::PERM_READ_ANY_JOBS)) {
                $jobs = $this->jobsTable->findAll('closing_date', 10, $offset);
                $totalJobs = $this->jobsTable->total();
            } else {
                $jobs = $this->jobsTable->find('user_id', $authUser->user_id, 'closing_date', 10, $offset);
                $totalJobs = $this->jobsTable->total('user_id', $authUser->user_id);
            }
        }

        return [
            'template' => '/admin/jobs/index.html.php',
            'title' => 'Admin - Jobs',
            'variables' => [
                'authUser' => $authUser,
                'categories' => $this->categoriesTable->findAll(),
                'categoryId' => $this->get['category'] ?? null,
                'currentPage' => $page,
                'jobs' => $jobs,
                'locationId' => $this->get['location'] ?? null,
                'locations' => $this->locationsTable->findAll(),
                'totalJobs' => $totalJobs
            ]
        ];
    }

    public function update($errors = [])
    {
        if (empty($this->post['job'])) {
            if (isset($this->get['id'])) {
                $job = $this->jobsTable->findById($this->get['id']);
            }
        } else {
            $job = new \JosJobs\Entity\Job(
                $this->applicationsTable,
                $this->categoriesTable,
                $this->locationsTable,
                $this->usersTable
            );

            foreach ($this->post['job'] as $key => $value) {
                $job->$key = $value;
            }
        }

        $title = 'Admin - Jobs - ';
        $title .= isset($this->get['id']) ? 'Update' : 'Create';


        return [
            'template' => '/admin/jobs/update.html.php',
            'title' => $title,
            'variables' => [
                'authUser' => $this->authentication->getUser(),
                'categories' => $this->categoriesTable->findAll(),
                'errors' => $errors,
                'job' => $job ?? null,
                'locations' => $this->locationsTable->findAll()
            ]
        ];
    }

    public function saveUpdate()
    {
        // Extract the job array from $this->post field
        $job = $this->post['job'];

        // Tidy up the form data
        $job['title'] = trim($job['title']);
        $job['description'] = trim($job['description']);
        $job['salary'] = trim($job['salary']);

        // Run form validation passing the data from the form
        $errors = $this->validateForm($job);

        // If the form is valid perform create/update
        // action or show the form again with errors.
        if (empty($errors)) {
            // Create/Update record in database
            $this->authentication->getUser()->addJob($job);

            // Redirect the user to jobs page
            header('location: /admin/jobs/');

            // Return status code from action
            return http_response_code();
        } else {
            return $this->update($errors);
        }
    }

    public function validateForm($job)
    {
        // Declare an empty array to store potential errors
        $errors = [];

        // Validate the job title field
        if (empty($job['title'])) {
            $errors[] = 'Title cannot be blank';
        } elseif (strlen($job['title']) >= 255) {
            $errors[] = 'Title exceeds max length of 255 characters';
        }

        // Validate the job description field
        if (empty($job['description'])) {
            $errors[] = 'Description cannot be blank';
        } elseif (strlen($job['description']) >= 8191) {
            $errors[] = 'Description exceeds max length of 8191 characters';
        }

        // Validate the job salary field
        // TODO: Potentially remove to permit non-disclosure of salary - likely
        // TODO: Potentially overhaul with low, high and frequency fields - unlikely
        if (empty($job['salary'])) {
            $errors[] = 'Salary cannot be blank';
        } elseif (strlen($job['salary']) >= 255) {
            $errors[] = 'Salary exceeds max length of 255 characters';
        }

        // Validate the job category field
        if (!isset($job['category_id'])) {
            $errors[] = 'A category must be selected';
        }

        // Validate the job location field
        if (!isset($job['location_id'])) {
            $errors[] = 'A location must be selected';
        }

        // Validate the job closing date field
        if (empty($job['closing_date'])) {
            $errors[] = 'Closing date cannot be blank';
        } else {
            // Some of the following code was derived from the following
            // Stack Overflow post by Amal Murali
            // https://stackoverflow.com/questions/19271381/
            // Accessed 30 Mar 2020
            $closingDate = \DateTime::createFromFormat('Y-m-d', $job['closing_date']);
            $now = new \DateTime();
            $nowP1Y = (new \DateTime())->modify('+1 year');

            // check date is valid format
            if (!$closingDate && $closingDate->format('Y-m-d') !== $job['closing_date']) {
                $errors[] = 'Invalid closing date and/or format';
            } elseif ($closingDate <= $now) { // check date is after today
                $errors[] = 'Closing date must be after today';
            } elseif ($closingDate >= $nowP1Y) { // check date is before 1 year into the future
                $errors[] = 'Closing date must be less than a year in advance';
            }
        }

        // Return any form validation errors
        return $errors;
    }

    public function archive()
    {
        // Get the current authenticated user
        $authUser = $this->authentication->getUser();

        // Get the job object to be archived
        $job = $this->jobsTable->findById($this->post['job_id']);

        // If the authenticated user doesn't own the job being deleted and
        // isn't permitted to delete jobs then prevent delete action
        if (
            $job->user_id !== $authUser->user_id &&
            !$authUser->hasPermission(\JosJobs\Entity\User::PERM_DELETE_ANY_JOBS)
        ) {
            return;
        }

        // Perform the archive/un-archive operation on the job
        // ? Sets the is_archived field to the opposite of what it currently is
        $this->jobsTable->update([
            'job_id' => $this->post['job_id'],
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
        $job = $this->jobsTable->findById($this->post['job_id']);

        // If the authenticated user doesn't own the job being deleted and
        // isn't permitted to delete jobs then prevent delete action
        if (
            $job->user_id !== $authUser->user_id &&
            !$authUser->hasPermission(\JosJobs\Entity\User::PERM_DELETE_ANY_JOBS)
        ) {
            return;
        }

        // Perform the deletion of the job
        $this->jobsTable->deleteById($this->post['job_id']);

        // Redirect the user to jobs page
        header('location: /admin/jobs/');

        // Return status code from action
        return http_response_code();
    }

    public function apply($errors = [])
    {
        // If a job isn't specified return them to the list of jobs
        // TODO: Pass an error message to this page to explain the issue.
        if (!isset($this->get['id'])) {
            header('location: /jobs/');
            return;
        }

        // Retrieve the specified job
        $job = $this->jobsTable->findById($this->get['id']);

        return [
            'template' => '/jobs/apply.html.php',
            'title' => 'Jobs - Apply',
            'variables' => [
                'application' => $this->post['application'] ?? null,
                'authUser' => $this->authentication->getUser(),
                'errors' => $errors,
                'job' => $job ?? null
            ]
        ];
    }

    public function saveApply()
    {
        // Extract the application array from $this->post field
        $application = $this->post['application'];

        // Trim unnecessary whitespace on text fields
        $application['name'] = trim($application['name']);
        $application['email'] = trim($application['email']);
        $application['details'] = trim($application['details']);

        // Update the record's cv field with the new filename
        $application['cv'] = $this->fileUpload->getNewFileName();

        // Run form validation passing the data from the form
        $errors = $this->validateApplyForm($application);

        // If the form is valid perform create/update
        // action or show the form again with errors.
        if (empty($errors)) {
            if ($this->fileUpload->upload()) {
                // Create record in database
                $this->applicationsTable->insert($application);

                // Redirect the user to job listings page
                header('location: /jobs/');

                // Return status code from action
                return http_response_code();
            } else {
                $errors[] = 'CV upload failed. Please try again later or make an enquiry on our contact page.';
                return $this->apply($errors);
            }
        } else {
            return $this->apply($errors);
        }
    }

    public function validateApplyForm($application)
    {
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

        // Validate cv file upload
        $errors = array_merge($errors, $this->fileUpload->checkFile());

        return $errors;
    }

    public function applications()
    {
        // If a job isn't specified return them to the list of jobs
        // TODO: Pass an error message to this page to explain the issue.
        if (!isset($this->get['id'])) {
            header('location: /admin/jobs/');
        }

        // Retrieve the specified job
        $job = $this->jobsTable->findById($this->get['id']);

        // If a job isn't found return them to the list of jobs
        // TODO: Pass an error message to this page to explain the issue.
        if (empty($job)) {
            header('location: /admin/jobs');
        }

        return [
            'template' => '/admin/jobs/applications/index.html.php',
            'title' => 'Admin - Jobs - Applications',
            'variables' => [
                'authUser' => $this->authentication->getUser(),
                'job' => $job ?? null
            ]
        ];
    }
}
