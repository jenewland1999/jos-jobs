<?php

/**
 * Admin Jobs Controller for Jo's Jobs.
 * 
 * PHP Version 7
 * 
 * @category  Default
 * @package   Default
 * @author    Jordan Newland <github@jenewland.me.uk>
 * @copyright 2020 Jordan Newland
 * @license   All Rights Reserved
 * @link      https://github.com/jenewland1999/
 */

require __DIR__ . '/../../../includes/DatabaseConnection.php';
require __DIR__ . '/../../../classes/DatabaseTable.php';
require __DIR__ . '/../../../includes/LoadTemplate.php';

session_start();

$applicant = new DatabaseTable($pdo, 'applicants', 'id');
$category = new DatabaseTable($pdo, 'category', 'id');
$job = new DatabaseTable($pdo, 'job', 'id');

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $baseJobs = $job->findAll();
    $jobs = [];

    foreach ($baseJobs as $job) {
        $jobs[] = [
            'id' => $job['id'],
            'title' => $job['title'],
            'description' => $job['description'],
            'salary' => $job['salary'],
            'closingDate' => $job['closingDate'],
            'categoryId' => $job['categoryId'],
            'location' => $job['location'],
            'applicantCount' => $applicant->totalBy('jobId', $job['id'])
        ];
    }

    $templateVars = [
        'isLoggedIn' => true,
        'jobs' => $jobs
    ];

} else {
    $templateVars = [
        'isLoggedIn' => false
    ];
}

echo loadTemplate(
    __DIR__ . '/../../../templates/layout.html.php', 
    [
        'title' => 'Admin - Jobs',
        'categories' => $category->findAll(),
        'output' => loadTemplate(
            __DIR__ . '/../../../templates/admin/jobs/index.html.php',
            $templateVars
        )
    ]
);
