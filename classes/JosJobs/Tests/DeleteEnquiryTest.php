<?php

namespace JosJobs\Tests;

class DeleteEnquiryTest extends \PHPUnit\Framework\TestCase
{
    private $authentication;
    private $enquiriesTable;
    private $usersTable;

    public function setUp(): void
    {
        $this->authentication = $this->createMock(\CupOfPHP\Authentication::class);
        $this->enquiriesTable = $this->createMock(\CupOfPHP\DatabaseTable::class);
        $this->usersTable = $this->createMock(\CupOfPHP\DatabaseTable::class);
    }

    /**
     * Given valid data, a category is delete and the
     * user is redirected to /admin/categories/.
     *
     * @runInSeparateProcess
     */
    public function testValid()
    {
        $testPostData = [
            'enquiry_id' => 100
        ];

        $enquiriesTable = $this->enquiriesTable;
        $enquiriesTable->expects($this->once())
                        ->method('deleteById')
                        ->with(
                            $this->equalTo($testPostData['enquiry_id'])
                        );

        $enquiryController = new \JosJobs\Controllers\Enquiry(
            $this->authentication,
            $enquiriesTable,
            $this->usersTable,
            [],
            $testPostData
        );

        $this->assertEquals($enquiryController->delete(), 302);
    }
}
