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

// Fetch a list of categories
$categories = $category->findAll();

// Fetch a list of locations (using the location field on job table)
// TODO: Consider making locations a separate table?
$stmt = $pdo->prepare('SELECT DISTINCT(`location`) FROM `job`');
$stmt->execute();

$locations = [];
foreach ($stmt->fetchAll() as $location) {
    $locations[] = $location['location'];
}

if (isset($_GET['category']) && isset($_GET['location'])) {
    $heading = sprintf(
        '%s Jobs in %s',
        $category->find('id', $_GET['category'])[0]['name'],
        $_GET['location']
    );
    $stmt = $pdo->prepare(
        'SELECT * 
         FROM `job` 
         WHERE closingDate > NOW() 
         AND categoryId = :id 
         AND location LIKE :loc'
    );
    $stmt->execute(
        [
            'id' => $_GET['category'],
            'loc' => '%' . $_GET['location'] . '%'
        ]
    );
    $jobs = $stmt->fetchAll();
} else if (isset($_GET['category'])) {
    $heading = sprintf(
        '%s Jobs',
        $category->find('id', $_GET['category'])[0]['name']
    );
    $stmt = $pdo->prepare(
        'SELECT * 
         FROM `job` 
         WHERE closingDate > NOW() 
         AND categoryId = :id'
    );
    $stmt->execute(
        [
            'id' => $_GET['category']
        ]
    );
    $jobs = $stmt->fetchAll();
} else if (isset($_GET['location'])) {
    $heading = sprintf(
        'Jobs in %s',
        $_GET['location']
    );
    $stmt = $pdo->prepare(
        'SELECT * 
         FROM `job` 
         WHERE closingDate > NOW()
         AND location LIKE :loc'
    );
    $stmt->execute(
        [
            'loc' => '%' . $_GET['location'] . '%'
        ]
    );
    $jobs = $stmt->fetchAll();
} else {
    $heading = 'All Jobs';
    $stmt = $pdo->prepare('SELECT * FROM `job` WHERE closingDate > NOW()');
    $stmt->execute();
    $jobs = $stmt->fetchAll();
}

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
                'locations' => $locations,
                'jobs' => $jobs
            ]
        )
    ]
);
