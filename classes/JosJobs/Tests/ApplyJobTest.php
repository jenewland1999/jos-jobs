<?php

namespace JosJobs\Tests;

class ApplyJobTest extends \PHPUnit\Framework\TestCase
{
    private $authentication;
    private $applicationsTable;
    private $categoriesTable;
    private $jobsTable;
    private $locationsTable;
    private $usersTable;

    public function setUp(): void
    {
        $this->authentication = $this->createMock(\CupOfPHP\Authentication::class);
        $this->applicationsTable = $this->createMock(\CupOfPHP\DatabaseTable::class);
        $this->categoriesTable = $this->createMock(\CupOfPHP\DatabaseTable::class);
        $this->jobsTable = $this->createMock(\CupOfPHP\DatabaseTable::class);
        $this->locationsTable = $this->createMock(\CupOfPHP\DatabaseTable::class);
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
        $testGetData = [
            'id' => 1
        ];

        $testPostData = [
            'application' => [
               'name' => 'John Smith',
               'email' => 'john.smith@example.org',
               'details' => '',
               'job_id' => 1
            ]
        ];

        $constructorArgs = [
            [
                'cv' => [
                    'name' => 'my-resume.pdf',
                    'type' => 'application/pdf',
                    'tmp_name' => '/tmp/phpIbsIbf',
                    'error' => 0,
                    'size' => 25000
                ]
            ],
            'cv',
            [
                'uploadsDir' => '/uploads/cvs/',
                'namePrefix' => 'cv_',
                'validFileExts' => ['docx', 'doc', 'pdf', 'rtf'],
                'maxFileSizeMB' => 0.5
            ]
        ];
        $fileUpload = $this->getMockBuilder('\CupOfPHP\FileUpload')
                           ->setConstructorArgs($constructorArgs)
                           ->setMethods(['upload', 'checkFile', 'getNewFileName'])
                           ->getMock();

        $fileUpload->expects($this->any())
                   ->method('upload')
                   ->will($this->returnValue(true));

        $fileUpload->expects($this->once())
                   ->method('checkFile')
                   ->will($this->returnValue([]));

        $jobController = new \JosJobs\Controllers\Job(
            $this->authentication,
            $this->applicationsTable,
            $this->categoriesTable,
            $this->jobsTable,
            $this->locationsTable,
            $this->usersTable,
            $testGetData,
            $testPostData,
            $fileUpload
        );

        $this->assertEquals($jobController->saveApply(), 302);
    }

    /**
     * Given invalid data, a job is not created and the
     * user is shown the form again with errors.
     */
    public function testNullName()
    {
        $testGetData = [
            'id' => 1
        ];

        $testPostData = [
            'application' => [
               'name' => '',
               'email' => 'john.smith@example.org',
               'details' => '',
               'job_id' => 1
            ]
        ];

        $constructorArgs = [
            [
                'cv' => [
                    'name' => 'my-resume.pdf',
                    'type' => 'application/pdf',
                    'tmp_name' => '/tmp/phpIbsIbf',
                    'error' => 0,
                    'size' => 25000
                ]
            ],
            'cv',
            [
                'uploadsDir' => '/uploads/cvs/',
                'namePrefix' => 'cv_',
                'validFileExts' => ['docx', 'doc', 'pdf', 'rtf'],
                'maxFileSizeMB' => 0.5
            ]
        ];
        $fileUpload = $this->getMockBuilder('\CupOfPHP\FileUpload')
                           ->setConstructorArgs($constructorArgs)
                           ->setMethods(['upload', 'checkFile', 'getNewFileName'])
                           ->getMock();

        $fileUpload->expects($this->any())
                   ->method('upload')
                   ->will($this->returnValue(true));

        $fileUpload->expects($this->once())
                   ->method('checkFile')
                   ->will($this->returnValue([]));

        $jobController = new \JosJobs\Controllers\Job(
            $this->authentication,
            $this->applicationsTable,
            $this->categoriesTable,
            $this->jobsTable,
            $this->locationsTable,
            $this->usersTable,
            $testGetData,
            $testPostData,
            $fileUpload
        );

        $this->assertEquals(
            $jobController->saveApply()['template'],
            '/jobs/apply.html.php'
        );
    }

    /**
     * Given invalid data, a job is not created and the
     * user is shown the form again with errors.
     */
    public function testNullEmail()
    {
        $testGetData = [
            'id' => 1
        ];

        $testPostData = [
            'application' => [
               'name' => 'John Smith',
               'email' => '',
               'details' => '',
               'job_id' => 1
            ]
        ];

        $constructorArgs = [
            [
                'cv' => [
                    'name' => 'my-resume.pdf',
                    'type' => 'application/pdf',
                    'tmp_name' => '/tmp/phpIbsIbf',
                    'error' => 0,
                    'size' => 25000
                ]
            ],
            'cv',
            [
                'uploadsDir' => '/uploads/cvs/',
                'namePrefix' => 'cv_',
                'validFileExts' => ['docx', 'doc', 'pdf', 'rtf'],
                'maxFileSizeMB' => 0.5
            ]
        ];
        $fileUpload = $this->getMockBuilder('\CupOfPHP\FileUpload')
                           ->setConstructorArgs($constructorArgs)
                           ->setMethods(['upload', 'checkFile', 'getNewFileName'])
                           ->getMock();

        $fileUpload->expects($this->any())
                   ->method('upload')
                   ->will($this->returnValue(true));

        $fileUpload->expects($this->once())
                   ->method('checkFile')
                   ->will($this->returnValue([]));

        $jobController = new \JosJobs\Controllers\Job(
            $this->authentication,
            $this->applicationsTable,
            $this->categoriesTable,
            $this->jobsTable,
            $this->locationsTable,
            $this->usersTable,
            $testGetData,
            $testPostData,
            $fileUpload
        );

        $this->assertEquals(
            $jobController->saveApply()['template'],
            '/jobs/apply.html.php'
        );
    }

    /**
     * Given invalid data, a job is not created and the
     * user is shown the form again with errors.
     */
    public function testNullAll()
    {
        $testGetData = [
            'id' => 1
        ];

        $testPostData = [
            'application' => [
               'name' => '',
               'email' => '',
               'details' => '',
               'job_id' => null
            ]
        ];

        $constructorArgs = [
            [
                'cv' => [
                    'name' => 'my-resume.pdf',
                    'type' => 'application/pdf',
                    'tmp_name' => '/tmp/phpIbsIbf',
                    'error' => 0,
                    'size' => 25000
                ]
            ],
            'cv',
            [
                'uploadsDir' => '/uploads/cvs/',
                'namePrefix' => 'cv_',
                'validFileExts' => ['docx', 'doc', 'pdf', 'rtf'],
                'maxFileSizeMB' => 0.5
            ]
        ];
        $fileUpload = $this->getMockBuilder('\CupOfPHP\FileUpload')
                           ->setConstructorArgs($constructorArgs)
                           ->setMethods(['upload', 'checkFile', 'getNewFileName'])
                           ->getMock();

        $fileUpload->expects($this->any())
                   ->method('upload')
                   ->will($this->returnValue(true));

        $fileUpload->expects($this->once())
                   ->method('checkFile')
                   ->will($this->returnValue([]));

        $jobController = new \JosJobs\Controllers\Job(
            $this->authentication,
            $this->applicationsTable,
            $this->categoriesTable,
            $this->jobsTable,
            $this->locationsTable,
            $this->usersTable,
            $testGetData,
            $testPostData,
            $fileUpload
        );

        $this->assertEquals(
            $jobController->saveApply()['template'],
            '/jobs/apply.html.php'
        );
    }

    /**
     * Given invalid data, a job is not created and the
     * user is shown the form again with errors.
     */
    public function testNameExceedsLength()
    {
        $testGetData = [
            'id' => 1
        ];

        $testPostData = [
            'application' => [
               'name' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quia sequi, ex deleniti officia pariatur adipisci quisquam reprehenderit delectus excepturi libero totam eligendi iusto nam nobis molestiae. Modi et eius id, sapiente doloremque delectus dolore beatae.',
               'email' => 'john.smith@example.org',
               'details' => '',
               'job_id' => null
            ]
        ];

        $constructorArgs = [
            [
                'cv' => [
                    'name' => 'my-resume.pdf',
                    'type' => 'application/pdf',
                    'tmp_name' => '/tmp/phpIbsIbf',
                    'error' => 0,
                    'size' => 25000
                ]
            ],
            'cv',
            [
                'uploadsDir' => '/uploads/cvs/',
                'namePrefix' => 'cv_',
                'validFileExts' => ['docx', 'doc', 'pdf', 'rtf'],
                'maxFileSizeMB' => 0.5
            ]
        ];
        $fileUpload = $this->getMockBuilder('\CupOfPHP\FileUpload')
                           ->setConstructorArgs($constructorArgs)
                           ->setMethods(['upload', 'checkFile', 'getNewFileName'])
                           ->getMock();

        $fileUpload->expects($this->any())
                   ->method('upload')
                   ->will($this->returnValue(true));

        $fileUpload->expects($this->once())
                   ->method('checkFile')
                   ->will($this->returnValue([]));

        $jobController = new \JosJobs\Controllers\Job(
            $this->authentication,
            $this->applicationsTable,
            $this->categoriesTable,
            $this->jobsTable,
            $this->locationsTable,
            $this->usersTable,
            $testGetData,
            $testPostData,
            $fileUpload
        );

        $this->assertEquals(
            $jobController->saveApply()['template'],
            '/jobs/apply.html.php'
        );
    }

    /**
     * Given invalid data, a job is not created and the
     * user is shown the form again with errors.
     */
    public function testEmailExceedsLength()
    {
        $testGetData = [
            'id' => 1
        ];

        $testPostData = [
            'application' => [
               'name' => 'John Smith',
               'email' => 'testtesttesttesttesttesttesttesttesttesttest.testtesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttest@testtesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttest.co.uk',
               'details' => '',
               'job_id' => null
            ]
        ];

        $constructorArgs = [
            [
                'cv' => [
                    'name' => 'my-resume.pdf',
                    'type' => 'application/pdf',
                    'tmp_name' => '/tmp/phpIbsIbf',
                    'error' => 0,
                    'size' => 25000
                ]
            ],
            'cv',
            [
                'uploadsDir' => '/uploads/cvs/',
                'namePrefix' => 'cv_',
                'validFileExts' => ['docx', 'doc', 'pdf', 'rtf'],
                'maxFileSizeMB' => 0.5
            ]
        ];
        $fileUpload = $this->getMockBuilder('\CupOfPHP\FileUpload')
                           ->setConstructorArgs($constructorArgs)
                           ->setMethods(['upload', 'checkFile', 'getNewFileName'])
                           ->getMock();

        $fileUpload->expects($this->any())
                   ->method('upload')
                   ->will($this->returnValue(true));

        $fileUpload->expects($this->once())
                   ->method('checkFile')
                   ->will($this->returnValue([]));

        $jobController = new \JosJobs\Controllers\Job(
            $this->authentication,
            $this->applicationsTable,
            $this->categoriesTable,
            $this->jobsTable,
            $this->locationsTable,
            $this->usersTable,
            $testGetData,
            $testPostData,
            $fileUpload
        );

        $this->assertEquals(
            $jobController->saveApply()['template'],
            '/jobs/apply.html.php'
        );
    }

    /**
     * Given invalid data, a job is not created and the
     * user is shown the form again with errors.
     */
    public function testInvalidEmailFmt()
    {
        $testGetData = [
            'id' => 1
        ];

        $testPostData = [
            'application' => [
               'name' => 'John Smith',
               'email' => 'john.smith[at]example[dot]org',
               'details' => '',
               'job_id' => null
            ]
        ];

        $constructorArgs = [
            [
                'cv' => [
                    'name' => 'my-resume.pdf',
                    'type' => 'application/pdf',
                    'tmp_name' => '/tmp/phpIbsIbf',
                    'error' => 0,
                    'size' => 25000
                ]
            ],
            'cv',
            [
                'uploadsDir' => '/uploads/cvs/',
                'namePrefix' => 'cv_',
                'validFileExts' => ['docx', 'doc', 'pdf', 'rtf'],
                'maxFileSizeMB' => 0.5
            ]
        ];
        $fileUpload = $this->getMockBuilder('\CupOfPHP\FileUpload')
                           ->setConstructorArgs($constructorArgs)
                           ->setMethods(['upload', 'checkFile', 'getNewFileName'])
                           ->getMock();

        $fileUpload->expects($this->any())
                   ->method('upload')
                   ->will($this->returnValue(true));

        $fileUpload->expects($this->once())
                   ->method('checkFile')
                   ->will($this->returnValue([]));

        $jobController = new \JosJobs\Controllers\Job(
            $this->authentication,
            $this->applicationsTable,
            $this->categoriesTable,
            $this->jobsTable,
            $this->locationsTable,
            $this->usersTable,
            $testGetData,
            $testPostData,
            $fileUpload
        );

        $this->assertEquals(
            $jobController->saveApply()['template'],
            '/jobs/apply.html.php'
        );
    }

    /**
     * Given invalid data, a job is not created and the
     * user is shown the form again with errors.
     */
    public function testFileExceedsSize()
    {
        $testGetData = [
            'id' => 1
        ];

        $testPostData = [
            'application' => [
               'name' => 'John Smith',
               'email' => 'john.smith[at]example[dot]org',
               'details' => '',
               'job_id' => null
            ]
        ];

        $constructorArgs = [
            [
                'cv' => [
                    'name' => 'my-resume.pdf',
                    'type' => 'application/pdf',
                    'tmp_name' => '/tmp/phpIbsIbf',
                    'error' => 0,
                    'size' => 75000
                ]
            ],
            'cv',
            [
                'uploadsDir' => '/uploads/cvs/',
                'namePrefix' => 'cv_',
                'validFileExts' => ['docx', 'doc', 'pdf', 'rtf'],
                'maxFileSizeMB' => 0.5
            ]
        ];
        $fileUpload = $this->getMockBuilder('\CupOfPHP\FileUpload')
                           ->setConstructorArgs($constructorArgs)
                           ->setMethods(['upload', 'checkFile', 'getNewFileName'])
                           ->getMock();

        $fileUpload->expects($this->any())
                   ->method('upload')
                   ->will($this->returnValue(true));

        $fileUpload->expects($this->once())
                   ->method('checkFile')
                   ->will($this->returnValue(['File exceeds max size']));

        $jobController = new \JosJobs\Controllers\Job(
            $this->authentication,
            $this->applicationsTable,
            $this->categoriesTable,
            $this->jobsTable,
            $this->locationsTable,
            $this->usersTable,
            $testGetData,
            $testPostData,
            $fileUpload
        );

        $this->assertEquals(
            $jobController->saveApply()['template'],
            '/jobs/apply.html.php'
        );
    }

    /**
     * Given invalid data, a job is not created and the
     * user is shown the form again with errors.
     */
    public function testFileInvalidType()
    {
        $testGetData = [
            'id' => 1
        ];

        $testPostData = [
            'application' => [
               'name' => 'John Smith',
               'email' => 'john.smith[at]example[dot]org',
               'details' => '',
               'job_id' => null
            ]
        ];

        $constructorArgs = [
            [
                'cv' => [
                    'name' => 'my-resume.txt',
                    'type' => 'application/pdf',
                    'tmp_name' => '/tmp/phpIbsIbf',
                    'error' => 0,
                    'size' => 75000
                ]
            ],
            'cv',
            [
                'uploadsDir' => '/uploads/cvs/',
                'namePrefix' => 'cv_',
                'validFileExts' => ['docx', 'doc', 'pdf', 'rtf'],
                'maxFileSizeMB' => 0.5
            ]
        ];
        $fileUpload = $this->getMockBuilder('\CupOfPHP\FileUpload')
                           ->setConstructorArgs($constructorArgs)
                           ->setMethods(['upload', 'checkFile', 'getNewFileName'])
                           ->getMock();

        $fileUpload->expects($this->any())
                   ->method('upload')
                   ->will($this->returnValue(true));

        $fileUpload->expects($this->once())
                   ->method('checkFile')
                   ->will($this->returnValue(['File invalid type']));

        $jobController = new \JosJobs\Controllers\Job(
            $this->authentication,
            $this->applicationsTable,
            $this->categoriesTable,
            $this->jobsTable,
            $this->locationsTable,
            $this->usersTable,
            $testGetData,
            $testPostData,
            $fileUpload
        );

        $this->assertEquals(
            $jobController->saveApply()['template'],
            '/jobs/apply.html.php'
        );
    }
}
