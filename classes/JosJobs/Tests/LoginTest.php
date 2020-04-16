<?php

namespace JosJobs\Tests;

class LoginTest extends \PHPUnit\Framework\TestCase
{
    private $authentication;

    public function setUp()
    {
        $this->authentication = $this->createMock(\CupOfPHP\Authentication::class);
    }

    /**
     * @runInSeparateProcess
     */
    public function testValidLogin()
    {
        $testPostData = [
            'user' => [
                'email' => 'john@example.org',
                'password' => 'password'
            ]
        ];

        $authentication = $this->authentication;
        $authentication->expects($this->once())
                       ->method('login')
                       ->with(
                           $this->equalTo($testPostData['user']['email']),
                           $this->equalTo($testPostData['user']['password'])
                       )
                       ->willReturn(true);

        $authController = new \JosJobs\Controllers\Auth(
            $this->authentication,
            [],
            $testPostData
        );

        $this->assertEquals($authController->processLogin(), 302);
    }

    /**
     * @runInSeparateProcess
     */
    public function testValidDataButInvalidLogin()
    {
        $testPostData = [
            'user' => [
                'email' => 'john@example.org',
                'password' => 'password'
            ]
        ];

        $authentication = $this->authentication;
        $authentication->expects($this->once())
                       ->method('login')
                       ->with(
                           $this->equalTo($testPostData['user']['email']),
                           $this->equalTo($testPostData['user']['password'])
                       )
                       ->willReturn(false);

        $authController = new \JosJobs\Controllers\Auth(
            $this->authentication,
            [],
            $testPostData
        );

        $this->assertEquals(
            $authController->processLogin()['template'],
            '/id/login.html.php'
        );
    }

    public function testNullEmail()
    {
        $testPostData = [
            'user' => [
                'email' => '',
                'password' => 'password'
            ]
        ];

        $authController = new \JosJobs\Controllers\Auth(
            $this->authentication,
            [],
            $testPostData
        );

        $this->assertEquals(
            $authController->processLogin()['template'],
            '/id/login.html.php'
        );
    }

    public function testNullPassword()
    {
        $testPostData = [
            'user' => [
                'email' => 'john@example.org',
                'password' => ''
            ]
        ];

        $authController = new \JosJobs\Controllers\Auth(
            $this->authentication,
            [],
            $testPostData
        );

        $this->assertEquals(
            $authController->processLogin()['template'],
            '/id/login.html.php'
        );
    }

    public function testNullAll()
    {
        $testPostData = [
            'user' => [
                'email' => '',
                'password' => ''
            ]
        ];

        $authController = new \JosJobs\Controllers\Auth(
            $this->authentication,
            [],
            $testPostData
        );

        $this->assertEquals(
            $authController->processLogin()['template'],
            '/id/login.html.php'
        );
    }

    public function testInvalidEmail()
    {
        $testPostData = [
            'user' => [
                'email' => 'john[at]example.org',
                'password' => 'password'
            ]
        ];

        $authController = new \JosJobs\Controllers\Auth(
            $this->authentication,
            [],
            $testPostData
        );

        $this->assertEquals(
            $authController->processLogin()['template'],
            '/id/login.html.php'
        );
    }

    public function testExcessiveData()
    {
        $testPostData = [
            'user' => [
                'email' => 'testtesttesttesttesttesttesttesttesttesttest.testtesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttest@testtesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttest.co.uk',
                'password' => 'password'
            ]
        ];

        $authController = new \JosJobs\Controllers\Auth(
            $this->authentication,
            [],
            $testPostData
        );

        $this->assertEquals(
            $authController->processLogin()['template'],
            '/id/login.html.php'
        );
    }
}
