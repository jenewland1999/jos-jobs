<?php

/**
 * About Us Controller for Jo's Jobs.
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

echo loadTemplate(
    __DIR__ . '/../../templates/layout.html.php', 
    [
        'title' => 'About Us',
        'categories' => $category->findAll(),
        'output' => loadTemplate(
            __DIR__ . '/../../templates/about/index.html.php'
        )
    ]
);
