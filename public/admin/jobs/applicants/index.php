<?php

/**
 * Admin Jobs Applicants Controller for Jo's Jobs.
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

require __DIR__ . '/../../../../includes/DatabaseConnection.php';
require __DIR__ . '/../../../../classes/DatabaseTable.php';
require __DIR__ . '/../../../../includes/LoadTemplate.php';

session_start();

$applicant = new DatabaseTable($pdo, 'applicants', 'id');
$category = new DatabaseTable($pdo, 'category', 'id');
$job = new DatabaseTable($pdo, 'job', 'id');

$templateVars = [
    'applicants' => [],
    'isLoggedIn' => true,
    'job' => $job->find('id', $_GET['id'] ?? '')[0]
];

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $templateVars['applicants'] = $applicant->find('jobId', $_GET['id'] ?? '');
} else {
    $templateVars['isLoggedIn'] = false;
}

echo loadTemplate(
    __DIR__ . '/../../../../templates/layout.html.php', 
    [
        'title' => 'Admin - Jobs - Applicants',
        'categories' => $category->findAll(),
        'output' => loadTemplate(
            __DIR__ . '/../../../../templates/admin/jobs/applicants/index.html.php',
            $templateVars
        )
    ]
);
