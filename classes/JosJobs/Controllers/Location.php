<?php

namespace JosJobs\Controllers;

use CupOfPHP\DatabaseTable;

class Location
{
    private $authentication;
    private $locationsTable;
    private $jobsTable;
    private $get;
    private $post;

    public function __construct(
        \CupOfPHP\Authentication $authentication,
        DatabaseTable $locationsTable,
        DatabaseTable $jobsTable,
        array $get,
        array $post
    ) {
        $this->authentication = $authentication;
        $this->locationsTable = $locationsTable;
        $this->jobsTable = $jobsTable;
        $this->get = $get;
        $this->post = $post;
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

    public function update($errors = [])
    {
        if (empty($this->post['location'])) {
            if (isset($this->get['id'])) {
                $location = $this->locationsTable->findById($this->get['id']);
            }
        } else {
            $location = new \JosJobs\Entity\Location($this->jobsTable);

            foreach ($this->post['location'] as $key => $value) {
                $location->$key = $value;
            }
        }

        $title = 'Admin - Locations - ';
        $title .= isset($this->get['id']) ? 'Update' : 'Create';

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

    public function saveUpdate()
    {
        // Run form validation passing the data from the form
        $errors = $this->validateForm($this->post['location']);

        // If the form is valid perform create/update
        // action or show the form again with errors.
        if (empty($errors)) {
            // Create/Update record in database
            $this->locationsTable->save($this->post['location']);

            // Redirect the user to locations page
            header('location: /admin/locations/');

            // Return status code from action
            return http_response_code();
        } else {
            return $this->update($errors);
        }
    }

    public function validateForm($location)
    {
        // Declare an empty array to store potential errors
        $errors = [];

        // Validate the location name field
        if (empty($location['name'])) {
            $errors[] = 'Location name cannot be blank';
        } elseif (strlen($location['name']) > 255) {
            $errors[] = 'Location name exceeds 255 characters';
        }

        // Return any form validation errors
        return $errors;
    }

    public function delete()
    {
        // Perform the deletion of the location
        $this->locationsTable->deleteById($this->post['location_id']);

        // Redirect the user to locations page
        header('location: /admin/locations/');

        // Return status code from action
        return http_response_code();
    }
}
