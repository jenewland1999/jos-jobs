<?php

namespace JosJobs\Controllers;

use CupOfPHP\DatabaseTable;

class Root
{
    private $authentication;
    private $categoriesTable;
    private $jobsTable;

    public function __construct(
        \CupOfPHP\Authentication $authentication,
        DatabaseTable $categoriesTable,
        DatabaseTable $jobsTable
    ) {
        $this->authentication = $authentication;
        $this->categoriesTable = $categoriesTable;
        $this->jobsTable = $jobsTable;
    }

    public function home()
    {
        $categories = $this->categoriesTable->findAll();

        return [
            'template' => 'index.html.php',
            'title' => 'Homepage',
            'variables' => [
                'categories' => $categories
            ]
        ];
    }

    public function about()
    {
        $categories = $this->categoriesTable->findAll();

        return [
            'template' => '/about/index.html.php',
            'title' => 'About Us',
            'variables' => [
                'categories' => $categories
            ]
        ];
    }

    public function faq()
    {
        $categories = $this->categoriesTable->findAll();

        return [
            'template' => '/about/faq/index.html.php',
            'title' => 'About Us - FAQ',
            'variables' => [
                'categories' => $categories
            ]
        ];
    }

    public function dashboard()
    {
        return [
            'template' => '/admin/index.html.php',
            'title' => 'Admin - Dashboard',
            'variables' => [
                'authUser' => $this->authentication->getUser()
            ]
        ];
    }
}