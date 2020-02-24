<?php

/**
 * IT Jobs Controller for Jo's Jobs.
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

require __DIR__ . '/../includes/DatabaseConnection.php';
require __DIR__ . '/../classes/DatabaseTable.php';
require __DIR__ . '/../includes/LoadTemplate.php';

$category = new DatabaseTable($pdo, 'category', 'id');
$job = new DatabaseTable($pdo, 'job', 'id');

$jobs = array_filter(
    $job->findAll(), 
    function ($job) {
        $dateClosing = new DateTime($job['closingDate']);
        $dateNow = new DateTime();
        
        return $job['categoryId'] == 1 && $dateClosing > $dateNow;
    }
);

echo loadTemplate(
    __DIR__ . '/../templates/layout.html.php', 
    [
        'title' => $category->find('id', 1)[0]['name'],
        'categories' => $category->findAll(),
        'output' => loadTemplate(
            __DIR__ . '/../templates/jobs/it.html.php',
            [
                'jobs' => $jobs
            ]
        )
    ]
);
