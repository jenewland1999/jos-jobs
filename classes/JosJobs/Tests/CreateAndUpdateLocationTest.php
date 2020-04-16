<?php

namespace JosJobs\Tests;

class CreateAndUpdateLocationTest extends \PHPUnit\Framework\TestCase
{
    private $authentication;
    private $locationsTable;
    private $jobsTable;

    public function setUp()
    {
        $this->authentication = $this->createMock(\CupOfPHP\Authentication::class);
        $this->locationsTable = $this->createMock(\CupOfPHP\DatabaseTable::class);
        $this->jobsTable = $this->createMock(\CupOfPHP\DatabaseTable::class);
    }

    /**
     * Given valid data, a location is created and the
     * user is redirected to /admin/locations/.
     *
     * @runInSeparateProcess
     */
    public function testValid()
    {
        $testPostData = [
            'location' => [
                'name' => 'My New Location'
            ]
        ];

        $locationsTable = $this->locationsTable;
        $locationsTable->expects($this->once())
                        ->method('save')
                        ->with(
                            $this->equalTo($testPostData['location'])
                        );

        $locationController = new \JosJobs\Controllers\Location(
            $this->authentication,
            $locationsTable,
            $this->jobsTable,
            [],
            $testPostData
        );

        $this->assertEquals($locationController->saveUpdate(), 302);
    }

    /**
     * Given invalid data, a location is not created and the
     * user is shown the form again with errors.
     */
    public function testNullData()
    {
        $testPostData = [
            'location' => [
                'name' => ''
            ]
        ];

        $locationController = new \JosJobs\Controllers\Location(
            $this->authentication,
            $this->locationsTable,
            $this->jobsTable,
            [],
            $testPostData
        );

        $this->assertEquals(
            $locationController->saveUpdate()['template'],
            '/admin/locations/update.html.php'
        );
    }

    /**
     * Given data that exceeds the max capacity, a location
     * is not created and the user is shown the form again
     * with errors.
     */
    public function testExcessiveData()
    {
        $testPostData = [
            'location' => [
                'name' => 'locationNameExceeds255Characters locationNameExceeds255Characters locationNameExceeds255Characters locationNameExceeds255Characters locationNameExceeds255Characters locationNameExceeds255Characters locationNameExceeds255Characters locationNameExceeds255Characters'
            ]
        ];

        $locationController = new \JosJobs\Controllers\Location(
            $this->authentication,
            $this->locationsTable,
            $this->jobsTable,
            [],
            $testPostData
        );

        $this->assertEquals(
            $locationController->saveUpdate()['template'],
            '/admin/locations/update.html.php'
        );
    }
}
