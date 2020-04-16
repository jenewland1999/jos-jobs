<?php

namespace JosJobs\Tests;

class CreateAndUpdateUserTest extends \PHPUnit\Framework\TestCase
{
    private $authentication;
    private $jobsTable;
    private $usersTable;

    public function setUp()
    {
        $this->authentication = $this->createMock(\CupOfPHP\Authentication::class);
        $this->jobsTable = $this->createMock(\CupOfPHP\DatabaseTable::class);
        $this->usersTable = $this->createMock(\CupOfPHP\DatabaseTable::class);
    }

    /**
     * Given valid data, a job is created and the
     * user is redirected to /admin/jobs/.
     *
     * @runInSeparateProcess
     */
    public function testValid()
    {
        $testPostData = [
            'user' => [
                'first_name' => 'John',
                'last_name' => 'Smith',
                'email' => 'john.smith@example.org',
                'password' => 'password'
            ]
        ];

        $usersTable = $this->usersTable;
        $usersTable->expects($this->once())
                        ->method('save')
                        ->with(
                            $this->anything()
                        );

        $userController = new \JosJobs\Controllers\User(
            $this->authentication,
            $this->jobsTable,
            $usersTable,
            [],
            $testPostData
        );

        $this->assertEquals($userController->saveUpdate(), 302);
    }

    /**
     * Given invalid data, a job is not created and the
     * user is shown the form again with errors.
     */
    public function testNullFirstName()
    {
        $testPostData = [
            'user' => [
                'first_name' => '',
                'last_name' => 'Smith',
                'email' => 'john.smith@example.org',
                'password' => 'password'
            ]
        ];

        $userController = new \JosJobs\Controllers\User(
            $this->authentication,
            $this->jobsTable,
            $this->usersTable,
            [],
            $testPostData
        );

        $this->assertEquals(
            $userController->saveUpdate()['template'],
            '/admin/users/update.html.php'
        );
    }

    /**
     * Given invalid data, a job is not created and the
     * user is shown the form again with errors.
     */
    public function testNullLastName()
    {
        $testPostData = [
            'user' => [
                'first_name' => 'John',
                'last_name' => '',
                'email' => 'john.smith@example.org',
                'password' => 'password'
            ]
        ];

        $userController = new \JosJobs\Controllers\User(
            $this->authentication,
            $this->jobsTable,
            $this->usersTable,
            [],
            $testPostData
        );

        $this->assertEquals(
            $userController->saveUpdate()['template'],
            '/admin/users/update.html.php'
        );
    }

    /**
     * Given invalid data, a job is not created and the
     * user is shown the form again with errors.
     */
    public function testNullEmail()
    {
        $testPostData = [
            'user' => [
                'first_name' => 'John',
                'last_name' => 'Smith',
                'email' => '',
                'password' => 'password'
            ]
        ];

        $userController = new \JosJobs\Controllers\User(
            $this->authentication,
            $this->jobsTable,
            $this->usersTable,
            [],
            $testPostData
        );

        $this->assertEquals(
            $userController->saveUpdate()['template'],
            '/admin/users/update.html.php'
        );
    }

    /**
     * Given invalid data, a job is not created and the
     * user is shown the form again with errors.
     */
    public function testNullPassword()
    {
        $testPostData = [
            'user' => [
                'first_name' => 'John',
                'last_name' => 'Smith',
                'email' => 'john.smith@example.org',
                'password' => ''
            ]
        ];

        $userController = new \JosJobs\Controllers\User(
            $this->authentication,
            $this->jobsTable,
            $this->usersTable,
            [],
            $testPostData
        );

        $this->assertEquals(
            $userController->saveUpdate()['template'],
            '/admin/users/update.html.php'
        );
    }

    /**
     * Given invalid data, a job is not created and the
     * user is shown the form again with errors.
     */
    public function testNullMultiple()
    {
        $testPostData = [
            'user' => [
                'first_name' => '',
                'last_name' => 'Smith',
                'email' => '',
                'password' => 'password'
            ]
        ];

        $userController = new \JosJobs\Controllers\User(
            $this->authentication,
            $this->jobsTable,
            $this->usersTable,
            [],
            $testPostData
        );

        $this->assertEquals(
            $userController->saveUpdate()['template'],
            '/admin/users/update.html.php'
        );
    }

    /**
     * Given invalid data, a job is not created and the
     * user is shown the form again with errors.
     */
    public function testNullAll()
    {
        $testPostData = [
            'user' => [
                'first_name' => '',
                'last_name' => '',
                'email' => '',
                'password' => ''
            ]
        ];

        $userController = new \JosJobs\Controllers\User(
            $this->authentication,
            $this->jobsTable,
            $this->usersTable,
            [],
            $testPostData
        );

        $this->assertEquals(
            $userController->saveUpdate()['template'],
            '/admin/users/update.html.php'
        );
    }

    /**
     * Given invalid data, a job is not created and the
     * user is shown the form again with errors.
     */
    public function testFirstNameExceedsLength()
    {
        $testPostData = [
            'user' => [
                'first_name' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsum enim velit officiis praesentium nam recusandae reprehenderit! Perspiciatis, minima sint quasi placeat quam asperiores doloribus cupiditate in, deleniti eius beatae, hic cum exercitationem esse architecto?',
                'last_name' => 'Smith',
                'email' => 'john.smith@example.org',
                'password' => 'password'
            ]
        ];

        $userController = new \JosJobs\Controllers\User(
            $this->authentication,
            $this->jobsTable,
            $this->usersTable,
            [],
            $testPostData
        );

        $this->assertEquals(
            $userController->saveUpdate()['template'],
            '/admin/users/update.html.php'
        );
    }

    /**
     * Given invalid data, a job is not created and the
     * user is shown the form again with errors.
     */
    public function testLastNameExceedsLength()
    {
        $testPostData = [
            'user' => [
                'first_name' => 'John',
                'last_name' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsum enim velit officiis praesentium nam recusandae reprehenderit! Perspiciatis, minima sint quasi placeat quam asperiores doloribus cupiditate in, deleniti eius beatae, hic cum exercitationem esse architecto?',
                'email' => 'john.smith@example.org',
                'password' => 'password'
            ]
        ];

        $userController = new \JosJobs\Controllers\User(
            $this->authentication,
            $this->jobsTable,
            $this->usersTable,
            [],
            $testPostData
        );

        $this->assertEquals(
            $userController->saveUpdate()['template'],
            '/admin/users/update.html.php'
        );
    }

    /**
     * Given invalid data, a job is not created and the
     * user is shown the form again with errors.
     */
    public function testEmailExceedsLength()
    {
        $testPostData = [
            'user' => [
                'first_name' => 'John',
                'last_name' => 'Smith',
                'email' => 'testtesttesttesttesttesttesttesttesttesttest.testtesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttest@testtesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttest.co.uk',
                'password' => 'password'
            ]
        ];

        $userController = new \JosJobs\Controllers\User(
            $this->authentication,
            $this->jobsTable,
            $this->usersTable,
            [],
            $testPostData
        );

        $this->assertEquals(
            $userController->saveUpdate()['template'],
            '/admin/users/update.html.php'
        );
    }

    /**
     * Given invalid data, a job is not created and the
     * user is shown the form again with errors.
     */
    public function testMultipleExceedsLength()
    {
        $testPostData = [
            'user' => [
                'first_name' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsum enim velit officiis praesentium nam recusandae reprehenderit! Perspiciatis, minima sint quasi placeat quam asperiores doloribus cupiditate in, deleniti eius beatae, hic cum exercitationem esse architecto?',
                'last_name' => 'Smith',
                'email' => 'testtesttesttesttesttesttesttesttesttesttest.testtesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttest@testtesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttest.co.uk',
                'password' => 'password'
            ]
        ];

        $userController = new \JosJobs\Controllers\User(
            $this->authentication,
            $this->jobsTable,
            $this->usersTable,
            [],
            $testPostData
        );

        $this->assertEquals(
            $userController->saveUpdate()['template'],
            '/admin/users/update.html.php'
        );
    }

    /**
     * Given invalid data, a job is not created and the
     * user is shown the form again with errors.
     */
    public function testAllExceedsLength()
    {
        $testPostData = [
            'user' => [
                'first_name' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsum enim velit officiis praesentium nam recusandae reprehenderit! Perspiciatis, minima sint quasi placeat quam asperiores doloribus cupiditate in, deleniti eius beatae, hic cum exercitationem esse architecto?',
                'last_name' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsum enim velit officiis praesentium nam recusandae reprehenderit! Perspiciatis, minima sint quasi placeat quam asperiores doloribus cupiditate in, deleniti eius beatae, hic cum exercitationem esse architecto?',
                'email' => 'testtesttesttesttesttesttesttesttesttesttest.testtesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttest@testtesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttest.co.uk',
                'password' => 'password'
            ]
        ];

        $userController = new \JosJobs\Controllers\User(
            $this->authentication,
            $this->jobsTable,
            $this->usersTable,
            [],
            $testPostData
        );

        $this->assertEquals(
            $userController->saveUpdate()['template'],
            '/admin/users/update.html.php'
        );
    }

    /**
     * Given invalid data, a job is not created and the
     * user is shown the form again with errors.
     */
    public function testEmailInvalidFmt()
    {
        $testPostData = [
            'user' => [
                'first_name' => 'John',
                'last_name' => 'Smith',
                'email' => 'john.smith[at]example[dot]org',
                'password' => 'password'
            ]
        ];

        $userController = new \JosJobs\Controllers\User(
            $this->authentication,
            $this->jobsTable,
            $this->usersTable,
            [],
            $testPostData
        );

        $this->assertEquals(
            $userController->saveUpdate()['template'],
            '/admin/users/update.html.php'
        );
    }
}
