<?php

namespace JosJobs\Tests;

class DeleteLocationTest extends \PHPUnit\Framework\TestCase
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
     * Given valid data, a location is delete and the
     * user is redirected to /admin/categories/.
     *
     * @runInSeparateProcess
     */
    public function testValid()
    {
        $testPostData = [
            'location_id' => 100
        ];

        $locationsTable = $this->locationsTable;
        $locationsTable->expects($this->once())
                        ->method('deleteById')
                        ->with(
                            $this->equalTo($testPostData['location_id'])
                        );

        $locationController = new \JosJobs\Controllers\Location(
            $this->authentication,
            $locationsTable,
            $this->jobsTable,
            [],
            $testPostData
        );

        $this->assertEquals($locationController->delete(), 302);
    }
}
