<?php

/**
 * Application Page Controller for Jo's Jobs.
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

if (isset($_POST['submit'])) {
    if ($_FILES['cv']['error'] == 0) {
        $parts = explode('.', $_FILES['cv']['name']);
        $extension = end($parts);
        $fileName = uniqid() . '.' . $extension;

        move_uploaded_file($_FILES['cv']['tmp_name'], 'cvs/' . $fileName);

        $criteria = [
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'details' => $_POST['details'],
            'jobId' => $_POST['jobId'],
            'cv' => $fileName
        ];

        $stmt = $pdo->prepare(
            'INSERT INTO applicants (name, email, details, jobId, cv) 
             VALUES (:name, :email, :details, :jobId, :cv)'
        );

        $stmt->execute($criteria);

        echo 'Your application is complete. We will contact you after the closing date.';
    } else {
        echo 'There was an error uploading your CV';
    }
} else {
    $stmt = $pdo->prepare('SELECT * FROM job WHERE id = :id');

    $stmt->execute($_GET);

    $job = $stmt->fetch();
}

echo loadTemplate(
    __DIR__ . '/../templates/layout.html.php', 
    [
        'title' => 'Homepage',
        'categories' => $category->findAll(),
        'output' => loadTemplate(
            __DIR__ . '/../templates/jobs/apply.html.php',
            [
                'job' => $job
            ]
        )
    ]
);
