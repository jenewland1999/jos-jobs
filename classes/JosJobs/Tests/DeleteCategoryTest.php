<?php

namespace JosJobs\Tests;

class DeleteCategoryTest extends \PHPUnit\Framework\TestCase
{
    private $authentication;
    private $categoriesTable;
    private $jobsTable;

    public function setUp(): void
    {
        $this->authentication = $this->createMock(\CupOfPHP\Authentication::class);
        $this->categoriesTable = $this->createMock(\CupOfPHP\DatabaseTable::class);
        $this->jobsTable = $this->createMock(\CupOfPHP\DatabaseTable::class);
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
            'category_id' => 100
        ];

        $categoriesTable = $this->categoriesTable;
        $categoriesTable->expects($this->once())
                        ->method('deleteById')
                        ->with(
                            $this->equalTo($testPostData['category_id'])
                        );

        $categoryController = new \JosJobs\Controllers\Category(
            $this->authentication,
            $categoriesTable,
            $this->jobsTable,
            [],
            $testPostData
        );

        $this->assertEquals($categoryController->delete(), 302);
    }
}
