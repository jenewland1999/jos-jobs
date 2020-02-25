<?php

/**
 * Jobs Controller for Jo's Jobs.
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

require __DIR__ . '/../../includes/DatabaseConnection.php';
require __DIR__ . '/../../classes/DatabaseTable.php';
require __DIR__ . '/../../includes/LoadTemplate.php';

$category = new DatabaseTable($pdo, 'category', 'id');
$job = new DatabaseTable($pdo, 'job', 'id');

function isJobRunning($job) 
{
    $dateClosing = new DateTime($job['closingDate']);
    $dateNow = new DateTime();
    
    return $dateClosing > $dateNow;
}

$categories = $category->findAll();

if (isset($_GET['category'])) {
    $heading = $category->find('id', $_GET['category'])[0]['name'];
    $jobs = array_filter(
        $job->find('categoryId', $_GET['category']),
        'isJobRunning'
    );
} else {
    $heading = 'All';
    $jobs = array_filter(
        $job->findAll(), 
        'isJobRunning'
    );
}

// $job['categoryId'] == 2

echo loadTemplate(
    __DIR__ . '/../../templates/layout.html.php', 
    [
        'title' => 'Jobs',
        'categories' => $categories,
        'output' => loadTemplate(
            __DIR__ . '/../../templates/jobs/index.html.php',
            [
                'heading' => $heading,
                'categories' => $categories,
                'jobs' => $jobs
            ]
        )
    ]
);
