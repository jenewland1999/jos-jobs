<?php

include __DIR__ . '/../vendor/autoload.php';
include __DIR__ . '/../includes/connection.php';

$route = rtrim(ltrim(strtok($_SERVER['REQUEST_URI'], '?'), '/'), '/');
$method = $_SERVER['REQUEST_METHOD'];

$entryPoint = new \CupOfPHP\EntryPoint($pdo, $route, $method, new \JosJobs\JosJobsRoutes($pdo));
$entryPoint->run();
