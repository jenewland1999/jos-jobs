<?php

namespace JosJobs\Tests;

class CreateAndUpdateCategoryTest extends \PHPUnit\Framework\TestCase
{
    private $authentication;
    private $categoriesTable;
    private $jobsTable;

    public function setUp()
    {
        $this->authentication = $this->createMock(\CupOfPHP\Authentication::class);
        $this->categoriesTable = $this->createMock(\CupOfPHP\DatabaseTable::class);
        $this->jobsTable = $this->createMock(\CupOfPHP\DatabaseTable::class);
    }

    /**
     * Given valid data, a category is created and the
     * user is redirected to /admin/categories/.
     *
     * @runInSeparateProcess
     */
    public function testValid()
    {
        $testPostData = [
            'category' => [
                'name' => 'My New Category'
            ]
        ];

        $categoriesTable = $this->categoriesTable;
        $categoriesTable->expects($this->once())
                        ->method('save')
                        ->with(
                            $this->equalTo($testPostData['category'])
                        );

        $categoryController = new \JosJobs\Controllers\Category(
            $this->authentication,
            $categoriesTable,
            $this->jobsTable,
            [],
            $testPostData
        );

        $this->assertEquals($categoryController->saveUpdate(), 302);
    }

    /**
     * Given invalid data, a category is not created and the
     * user is shown the form again with errors.
     */
    public function testNullData()
    {
        $testPostData = [
            'category' => [
                'name' => ''
            ]
        ];

        $categoryController = new \JosJobs\Controllers\Category(
            $this->authentication,
            $this->categoriesTable,
            $this->jobsTable,
            [],
            $testPostData
        );

        $this->assertEquals(
            $categoryController->saveUpdate()['template'],
            '/admin/categories/update.html.php'
        );
    }

    /**
     * Given data that exceeds the max capacity, a category
     * is not created and the user is shown the form again
     * with errors.
     */
    public function testExcessiveData()
    {
        $testPostData = [
            'category' => [
                'name' => 'categoryNameExceeds255Characters categoryNameExceeds255Characters categoryNameExceeds255Characters categoryNameExceeds255Characters categoryNameExceeds255Characters categoryNameExceeds255Characters categoryNameExceeds255Characters categoryNameExceeds255Characters'
            ]
        ];

        $categoryController = new \JosJobs\Controllers\Category(
            $this->authentication,
            $this->categoriesTable,
            $this->jobsTable,
            [],
            $testPostData
        );

        $this->assertEquals(
            $categoryController->saveUpdate()['template'],
            '/admin/categories/update.html.php'
        );
    }
}
