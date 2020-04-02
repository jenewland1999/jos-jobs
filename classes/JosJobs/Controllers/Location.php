<?php

namespace JosJobs\Controllers;

use CupOfPHP\DatabaseTable;

class Location
{
    private $authentication;
    private $locationsTable;

    public function __construct(
        \CupOfPHP\Authentication $authentication,
        DatabaseTable $locationsTable
    ) {
        $this->authentication = $authentication;
        $this->locationsTable = $locationsTable;
    }

    public function read()
    {
        $locations = $this->locationsTable->findAll();

        return [
            'template' => '/admin/locations/index.html.php',
            'title' => 'Admin - Locations',
            'variables' => [
                'authUser' => $this->authentication->getUser(),
                'locations' => $locations
            ]
        ];
    }

    public function update()
    {
        if (isset($_GET['id'])) {
            $location = $this->locationsTable->findById($_GET['id']);
            $title = 'Admin - Locations - Update';
        } else {
            $title = 'Admin - Locations - Create';
        }

        return [
            'template' => '/admin/locations/update.html.php',
            'title' => $title,
            'variables' => [
                'authUser' => $this->authentication->getUser(),
                'location' => $location ?? null
            ]
        ];
    }

    public function saveUpdate()
    {
        $location = new \JosJobs\Entity\Location($this->locationsTable);

        foreach ($_POST['location'] as $key => $value) {
            $location->$key = $value;
        }

        $errors = [];

        if (empty($location->name)) {
            $errors[] = 'Location name cannot be blank.';
        } else {
            // Check if the location already exists on the site (This handles both updates and inserts)
            if (isset($_GET['id'])) {
                $exists = false;
                foreach ($this->locationsTable->findAll() as $record) {
                    if ($record->location_id === $location->location_id) {
                        continue;
                    }

                    if (strtolower($record->name) !== strtolower($location->name)) {
                        continue;
                    }

                    $exists = true;
                }

                if ($exists) {
                    $errors[] = 'A location with that name already exists.';
                }
            } else {
                if ($this->locationsTable->total('name', strtolower($location->name)) > 0) {
                    $errors[] = 'A location with that name already exists.';
                }
            }
        }

        if (empty($errors)) {
            $this->locationsTable->save($_POST['location']);

            header('location: /admin/locations/');
        } else {
            $title = 'Admin - Locations - ';
            $title .= isset($_GET['id']) ? 'Update' : 'Create';

            return [
                'template' => '/admin/locations/update.html.php',
                'title' => $title,
                'variables' => [
                    'authUser' => $this->authentication->getUser(),
                    'errors' => $errors,
                    'location' => $location ?? null
                ]
            ];
        }
    }

    public function delete()
    {
        // Get the current authenticated user
        $authUser = $this->authentication->getUser();

        // Get the location object selected for deletion
        $location = $this->locationsTable->findById($_POST['location_id']);

        // If the authenticated user isn't permitted to delete locations then
        // prevent delete action
        if (
            !$authUser->hasPermission(\JosJobs\Entity\User::PERM_DELETE_LOCATIONS)
        ) {
            return;
        }

        // Perform the deletion of the location
        $this->locationsTable->deleteById($_POST['location_id']);

        // Redirect the user to locations page
        header('location: /admin/locations/');
    }
}
