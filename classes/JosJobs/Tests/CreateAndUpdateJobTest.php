<?php

namespace JosJobs\Tests;

class CreateAndUpdateJobTest extends \PHPUnit\Framework\TestCase
{
    private $authentication;
    private $applicationsTable;
    private $categoriesTable;
    private $jobsTable;
    private $locationsTable;
    private $usersTable;
    private $fileUpload;

    public function setUp(): void
    {
        $this->authentication = $this->createMock(\CupOfPHP\Authentication::class);
        $this->applicationsTable = $this->createMock(\CupOfPHP\DatabaseTable::class);
        $this->categoriesTable = $this->createMock(\CupOfPHP\DatabaseTable::class);
        $this->jobsTable = $this->createMock(\CupOfPHP\DatabaseTable::class);
        $this->locationsTable = $this->createMock(\CupOfPHP\DatabaseTable::class);
        $this->usersTable = $this->createMock(\CupOfPHP\DatabaseTable::class);
        $this->fileUpload = $this->createMock(\CupOfPHP\FileUpload::class);
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
            'job' => [
                'title' => 'Sales Assistant',
                'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum illum voluptas sed odio ipsa, illo placeat quo tenetur corporis in sunt? Deserunt quibusdam labore explicabo aut ipsum, dolorum beatae? Sit?',
                'salary' => '£13,000 - £15,000',
                'category_id' => 1,
                'location_id' => 1,
                'closing_date' => (new \DateTime())->modify('+1 day')->format('Y-m-d')
            ]
        ];

        $authentication = $this->authentication;
        $authentication->expects($this->once())
                       ->method('getUser')
                       ->willReturn(new \JosJobs\Entity\User($this->jobsTable));

        $jobController = new \JosJobs\Controllers\Job(
            $authentication,
            $this->applicationsTable,
            $this->categoriesTable,
            $this->jobsTable,
            $this->locationsTable,
            $this->usersTable,
            [],
            $testPostData,
            $this->fileUpload
        );

        $this->assertEquals($jobController->saveUpdate(), 302);
    }

    /**
     * Given invalid data, a job is not created and the
     * user is shown the form again with errors.
     */
    public function testNullTitle()
    {
        $testPostData = [
            'job' => [
                'title' => '',
                'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum illum voluptas sed odio ipsa, illo placeat quo tenetur corporis in sunt? Deserunt quibusdam labore explicabo aut ipsum, dolorum beatae? Sit?',
                'salary' => '£13,000 - £15,000',
                'category_id' => 1,
                'location_id' => 1,
                'closing_date' => (new \DateTime())->modify('+1 day')->format('Y-m-d')
            ]
        ];

        $authentication = $this->authentication;
        $authentication->expects($this->once())
                       ->method('getUser')
                       ->willReturn(new \JosJobs\Entity\User($this->jobsTable));

        $jobController = new \JosJobs\Controllers\Job(
            $authentication,
            $this->applicationsTable,
            $this->categoriesTable,
            $this->jobsTable,
            $this->locationsTable,
            $this->usersTable,
            [],
            $testPostData,
            $this->fileUpload
        );

        $this->assertEquals(
            $jobController->saveUpdate()['template'],
            '/admin/jobs/update.html.php'
        );
    }

    /**
     * Given invalid data, a job is not created and the
     * user is shown the form again with errors.
     */
    public function testNullDesc()
    {
        $testPostData = [
            'job' => [
                'title' => 'Sales Assistant',
                'description' => '',
                'salary' => '£13,000 - £15,000',
                'category_id' => 1,
                'location_id' => 1,
                'closing_date' => (new \DateTime())->modify('+1 day')->format('Y-m-d')
            ]
        ];

        $authentication = $this->authentication;
        $authentication->expects($this->once())
                       ->method('getUser')
                       ->willReturn(new \JosJobs\Entity\User($this->jobsTable));

        $jobController = new \JosJobs\Controllers\Job(
            $authentication,
            $this->applicationsTable,
            $this->categoriesTable,
            $this->jobsTable,
            $this->locationsTable,
            $this->usersTable,
            [],
            $testPostData,
            $this->fileUpload
        );

        $this->assertEquals(
            $jobController->saveUpdate()['template'],
            '/admin/jobs/update.html.php'
        );
    }

    /**
     * Given invalid data, a job is not created and the
     * user is shown the form again with errors.
     */
    public function testNullSalary()
    {
        $testPostData = [
            'job' => [
                'title' => 'Sales Assistant',
                'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum illum voluptas sed odio ipsa, illo placeat quo tenetur corporis in sunt? Deserunt quibusdam labore explicabo aut ipsum, dolorum beatae? Sit?',
                'salary' => '',
                'category_id' => 1,
                'location_id' => 1,
                'closing_date' => (new \DateTime())->modify('+1 day')->format('Y-m-d')
            ]
        ];

        $authentication = $this->authentication;
        $authentication->expects($this->once())
                       ->method('getUser')
                       ->willReturn(new \JosJobs\Entity\User($this->jobsTable));

        $jobController = new \JosJobs\Controllers\Job(
            $authentication,
            $this->applicationsTable,
            $this->categoriesTable,
            $this->jobsTable,
            $this->locationsTable,
            $this->usersTable,
            [],
            $testPostData,
            $this->fileUpload
        );

        $this->assertEquals(
            $jobController->saveUpdate()['template'],
            '/admin/jobs/update.html.php'
        );
    }

    /**
     * Given invalid data, a job is not created and the
     * user is shown the form again with errors.
     */
    public function testNullCategory()
    {
        $testPostData = [
            'job' => [
                'title' => 'Sales Assistant',
                'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum illum voluptas sed odio ipsa, illo placeat quo tenetur corporis in sunt? Deserunt quibusdam labore explicabo aut ipsum, dolorum beatae? Sit?',
                'salary' => '£13,000 - £15,000',
                'category_id' => null,
                'location_id' => 1,
                'closing_date' => (new \DateTime())->modify('+1 day')->format('Y-m-d')
            ]
        ];

        $authentication = $this->authentication;
        $authentication->expects($this->once())
                       ->method('getUser')
                       ->willReturn(new \JosJobs\Entity\User($this->jobsTable));

        $jobController = new \JosJobs\Controllers\Job(
            $authentication,
            $this->applicationsTable,
            $this->categoriesTable,
            $this->jobsTable,
            $this->locationsTable,
            $this->usersTable,
            [],
            $testPostData,
            $this->fileUpload
        );

        $this->assertEquals(
            $jobController->saveUpdate()['template'],
            '/admin/jobs/update.html.php'
        );
    }

    /**
     * Given invalid data, a job is not created and the
     * user is shown the form again with errors.
     */
    public function testNullLocation()
    {
        $testPostData = [
            'job' => [
                'title' => 'Sales Assistant',
                'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum illum voluptas sed odio ipsa, illo placeat quo tenetur corporis in sunt? Deserunt quibusdam labore explicabo aut ipsum, dolorum beatae? Sit?',
                'salary' => '£13,000 - £15,000',
                'category_id' => 1,
                'location_id' => null,
                'closing_date' => (new \DateTime())->modify('+1 day')->format('Y-m-d')
            ]
        ];

        $authentication = $this->authentication;
        $authentication->expects($this->once())
                       ->method('getUser')
                       ->willReturn(new \JosJobs\Entity\User($this->jobsTable));

        $jobController = new \JosJobs\Controllers\Job(
            $authentication,
            $this->applicationsTable,
            $this->categoriesTable,
            $this->jobsTable,
            $this->locationsTable,
            $this->usersTable,
            [],
            $testPostData,
            $this->fileUpload
        );

        $this->assertEquals(
            $jobController->saveUpdate()['template'],
            '/admin/jobs/update.html.php'
        );
    }

    /**
     * Given invalid data, a job is not created and the
     * user is shown the form again with errors.
     */
    public function testNullClosingDate()
    {
        $testPostData = [
            'job' => [
                'title' => 'Sales Assistant',
                'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum illum voluptas sed odio ipsa, illo placeat quo tenetur corporis in sunt? Deserunt quibusdam labore explicabo aut ipsum, dolorum beatae? Sit?',
                'salary' => '£13,000 - £15,000',
                'category_id' => 1,
                'location_id' => 1,
                'closing_date' => null
            ]
        ];

        $authentication = $this->authentication;
        $authentication->expects($this->once())
                       ->method('getUser')
                       ->willReturn(new \JosJobs\Entity\User($this->jobsTable));

        $jobController = new \JosJobs\Controllers\Job(
            $authentication,
            $this->applicationsTable,
            $this->categoriesTable,
            $this->jobsTable,
            $this->locationsTable,
            $this->usersTable,
            [],
            $testPostData,
            $this->fileUpload
        );

        $this->assertEquals(
            $jobController->saveUpdate()['template'],
            '/admin/jobs/update.html.php'
        );
    }

    /**
     * Given invalid data, a job is not created and the
     * user is shown the form again with errors.
     */
    public function testNullAll()
    {
        $testPostData = [
            'job' => [
                'title' => '',
                'description' => '',
                'salary' => '',
                'category_id' => null,
                'location_id' => null,
                'closing_date' => null
            ]
        ];

        $authentication = $this->authentication;
        $authentication->expects($this->once())
                       ->method('getUser')
                       ->willReturn(new \JosJobs\Entity\User($this->jobsTable));

        $jobController = new \JosJobs\Controllers\Job(
            $authentication,
            $this->applicationsTable,
            $this->categoriesTable,
            $this->jobsTable,
            $this->locationsTable,
            $this->usersTable,
            [],
            $testPostData,
            $this->fileUpload
        );

        $this->assertEquals(
            $jobController->saveUpdate()['template'],
            '/admin/jobs/update.html.php'
        );
    }

    /**
     * Given invalid data, a job is not created and the
     * user is shown the form again with errors.
     */
    public function testNullMultiple()
    {
        $testPostData = [
            'job' => [
                'title' => '',
                'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum illum voluptas sed odio ipsa, illo placeat quo tenetur corporis in sunt? Deserunt quibusdam labore explicabo aut ipsum, dolorum beatae? Sit?',
                'salary' => '£13,000 - £15,000',
                'category_id' => 1,
                'location_id' => 1,
                'closing_date' => null
            ]
        ];

        $authentication = $this->authentication;
        $authentication->expects($this->once())
                       ->method('getUser')
                       ->willReturn(new \JosJobs\Entity\User($this->jobsTable));

        $jobController = new \JosJobs\Controllers\Job(
            $authentication,
            $this->applicationsTable,
            $this->categoriesTable,
            $this->jobsTable,
            $this->locationsTable,
            $this->usersTable,
            [],
            $testPostData,
            $this->fileUpload
        );

        $this->assertEquals(
            $jobController->saveUpdate()['template'],
            '/admin/jobs/update.html.php'
        );
    }

    /**
     * Given invalid data, a job is not created and the
     * user is shown the form again with errors.
     */
    public function testTitleExceedsLength()
    {
        $testPostData = [
            'job' => [
                'title' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Fugiat, neque. Modi ipsam deserunt, ex facere voluptas dolorum natus veniam praesentium cupiditate cum saepe illo aut expedita voluptatem dolores. Iusto porro unde optio quidem pariatur, enim tenetur placeat.',
                'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum illum voluptas sed odio ipsa, illo placeat quo tenetur corporis in sunt? Deserunt quibusdam labore explicabo aut ipsum, dolorum beatae? Sit?',
                'salary' => '£13,000 - £15,000',
                'category_id' => 1,
                'location_id' => 1,
                'closing_date' => (new \DateTime())->modify('+1 day')->format('Y-m-d')
            ]
        ];

        $authentication = $this->authentication;
        $authentication->expects($this->once())
                       ->method('getUser')
                       ->willReturn(new \JosJobs\Entity\User($this->jobsTable));

        $jobController = new \JosJobs\Controllers\Job(
            $authentication,
            $this->applicationsTable,
            $this->categoriesTable,
            $this->jobsTable,
            $this->locationsTable,
            $this->usersTable,
            [],
            $testPostData,
            $this->fileUpload
        );

        $this->assertEquals(
            $jobController->saveUpdate()['template'],
            '/admin/jobs/update.html.php'
        );
    }

    /**
     * Given invalid data, a job is not created and the
     * user is shown the form again with errors.
     */
    public function testDescExceedsLength()
    {
        $testPostData = [
            'job' => [
                'title' => 'Sales Assistant',
                'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Fugit exercitationem quae quisquam voluptate ullam nisi, quod aspernatur enim debitis numquam amet omnis tenetur, aperiam praesentium magnam labore recusandae cumque eveniet molestias expedita odit explicabo? Pariatur ipsum quibusdam dolorem facere nihil magni laudantium error numquam. Rem non veniam officia officiis necessitatibus. Eos quidem delectus ab facilis impedit, ducimus, non praesentium explicabo nobis quam doloremque, dolorum iste fuga enim adipisci libero a perferendis labore assumenda blanditiis earum culpa. Laudantium eveniet praesentium perferendis earum soluta unde atque at. Quam maiores labore libero, obcaecati perspiciatis cupiditate et quidem beatae illo voluptatum culpa eos magnam ipsum ab eligendi quas dolorum? Repellat odio dignissimos quod doloribus, corporis facere labore eaque voluptate porro voluptates consequatur quas voluptatum voluptas obcaecati? Unde quam consequatur maiores, doloremque voluptate quis explicabo corporis distinctio aliquam ratione asperiores fugit impedit laboriosam ex consequuntur ipsum officiis dolorem recusandae odit ducimus mollitia. A quia ut quas rerum consequatur excepturi at harum aspernatur delectus inventore! Voluptatibus sit incidunt modi in aliquam blanditiis velit dolor consequuntur nulla mollitia recusandae fuga, labore nihil harum eum. Consectetur molestias harum quod, nulla veritatis maxime optio rem sequi totam ea sapiente adipisci velit nesciunt dolorum! Ullam mollitia itaque ipsa autem illo, nemo cumque deserunt nihil consequatur, iusto iure blanditiis, earum a porro eos ipsum nisi exercitationem odio veniam. Ea fuga vel voluptas accusamus consequatur possimus neque libero recusandae, aut delectus aliquid eum excepturi, harum sit facere! Nihil maiores ad voluptatem, soluta nulla quod amet commodi quo doloribus sunt quidem fuga quaerat excepturi itaque, exercitationem impedit eligendi consequatur dolore veniam. Placeat alias repudiandae tempore? Possimus incidunt et quos laborum voluptate ex modi illum vel placeat, soluta consequatur doloremque est ducimus officiis neque deserunt in aut quaerat delectus perferendis qui. Vero saepe officia maiores cupiditate placeat facilis, necessitatibus accusantium impedit nulla ab. Asperiores quae corrupti odio. Doloremque delectus nulla enim perspiciatis necessitatibus? A voluptate possimus, eveniet omnis voluptatum accusantium autem, molestias nulla aperiam magnam doloribus fugiat sunt sequi dolorum quisquam exercitationem? Doloribus beatae soluta quas autem sapiente iure ducimus amet dolorum iusto nihil ad eum corporis inventore, eius laborum blanditiis eaque, ullam mollitia. Laboriosam sapiente animi laudantium iusto odio nihil a, similique omnis, minima, quis dicta aperiam maxime temporibus quae iste. Alias ab non libero voluptas ipsam minima a perferendis sed maxime velit autem maiores, hic perspiciatis inventore esse dolore dignissimos eaque numquam officiis nemo. Totam, veniam at consequuntur facilis facere dolore voluptatum aut officiis cupiditate delectus doloribus esse, omnis asperiores similique excepturi quasi reiciendis minima qui odit aliquid reprehenderit fuga a repellat eaque. Quas officiis natus cum magnam eligendi deleniti doloremque necessitatibus nulla, quis maxime ex, repudiandae dignissimos rerum earum facilis cumque eaque omnis enim! Officia ea tempore consequatur possimus iusto eum perferendis? Incidunt, ut eius, ratione explicabo recusandae obcaecati corporis minima, quaerat nam voluptates omnis? Quisquam praesentium tempora, aliquam eos deserunt reprehenderit harum vero ad veniam soluta odit aperiam necessitatibus iste reiciendis minus sapiente iure odio non doloremque numquam impedit veritatis aliquid pariatur voluptatum? Adipisci neque quo odio in sunt? Molestiae iste iusto blanditiis laudantium laboriosam, nulla hic dicta cum dolor doloribus facilis harum corporis eligendi sequi ad atque asperiores vel consectetur, dolore consequuntur? Excepturi facere nostrum sed sunt veritatis aliquid eaque maiores ullam numquam, reiciendis odio culpa quis deserunt? Atque quae perferendis porro omnis ducimus tempora, sed nisi pariatur enim quia accusamus temporibus praesentium obcaecati debitis dolor natus, numquam odit repudiandae doloremque optio voluptatum consequuntur in laboriosam? Possimus quae accusantium nemo commodi fuga suscipit velit perferendis dolore asperiores architecto quas, consequuntur omnis mollitia eaque natus. Velit perferendis aspernatur alias, ipsam earum sed quae quod modi sequi, unde, voluptate sapiente eos repudiandae rerum. Numquam at voluptatibus iste, quisquam assumenda ipsa aperiam ullam delectus excepturi nisi molestiae? Qui, suscipit. Cum fugiat labore praesentium sint rerum adipisci optio quos, voluptatem ducimus, quod consequatur assumenda nobis quasi? Deserunt, soluta ab? Maiores officia quisquam voluptates voluptatum velit, nisi corporis! Eius repudiandae ad sunt earum, a explicabo sit? Consequuntur maxime officia porro exercitationem quis ducimus impedit eius alias autem quidem nihil, excepturi amet natus fuga reiciendis doloremque necessitatibus esse ea totam, incidunt dolorum? Recusandae voluptate pariatur, quas, molestiae adipisci cum quos porro nemo, dolores tenetur harum error veniam labore in sequi eos asperiores magnam. Eaque non eius quis sapiente corporis voluptate inventore laborum quaerat perferendis nesciunt aperiam itaque, nostrum iusto obcaecati aspernatur incidunt! Quas, facere voluptas. Quibusdam, beatae eos. Harum earum dignissimos enim totam deserunt facere autem esse quo atque hic obcaecati impedit illum, voluptate ipsam fugiat fuga voluptates veritatis. Facilis, sit quo fuga magnam laboriosam ducimus fugit ab inventore adipisci enim, explicabo deserunt eaque temporibus voluptas soluta deleniti consequuntur quis quod, consectetur provident voluptatem eum? Ut, suscipit totam optio inventore rem voluptate cupiditate magni vitae assumenda officiis perferendis corrupti quibusdam numquam facilis dignissimos delectus quisquam! Hic ea odio alias corrupti omnis minima, repudiandae obcaecati? Assumenda adipisci exercitationem deleniti. Laborum, consequuntur? Hic porro sit repudiandae aliquid mollitia quidem ducimus praesentium veniam pariatur saepe maxime, natus doloremque possimus alias? Totam ipsa quos doloribus ipsum, labore nulla officia omnis odit eum fuga, at asperiores repudiandae officiis perspiciatis in nisi odio deserunt quas fugiat commodi non. Blanditiis odio, laborum labore tenetur accusamus excepturi reprehenderit veritatis vitae repellat laudantium nobis corporis! Soluta quos eum quidem sed sint porro, corporis quas ullam itaque nesciunt, asperiores et placeat dolores libero optio? Sapiente, dolore, quaerat nisi aliquid sit provident repellat labore, accusantium laborum nesciunt commodi amet magnam rerum. Deleniti illum possimus sed! Atque inventore sit dolore, id ad ut. Assumenda accusantium ipsam dignissimos similique excepturi, vitae animi, deserunt at necessitatibus corrupti accusamus nihil impedit nulla consequatur quis. Provident vitae autem laboriosam, doloremque officiis at deserunt dolor qui alias necessitatibus iure, nobis esse est saepe! Nobis dolorem minus dicta placeat quidem quisquam qui ex repellat molestiae. Quod nesciunt nisi rem atque, aliquam alias. Minima aliquam iusto tenetur accusamus mollitia possimus magni, deleniti adipisci eligendi. Possimus harum atque quidem fugit consequatur facere veniam porro perferendis iure mollitia molestias odit, ea nihil minus alias eum eos optio, illum repellat? Quidem ex eos, impedit eum asperiores architecto error temporibus officiis numquam recusandae. Sunt ducimus veritatis labore rem omnis! Laborum quasi et itaque ea ad voluptate minima voluptas qui. Nulla, architecto! Quidem pariatur impedit deleniti, veritatis ratione dolorum praesentium soluta vel dignissimos facilis illum recusandae distinctio amet provident! Sapiente, placeat in consequuntur id eaque aut sequi, voluptatem expedita, harum enim dignissimos ea accusamus impedit veniam mollitia quis blanditiis adipisci odit dicta aliquid ullam nostrum atque repellat? Voluptatum distinctio ratione maiores adipisci id sequi, ipsum porro autem ea labore sapiente dolore, suscipit tempore quae, dolor explicabo placeat et libero corporis! Incidunt ipsum ducimus, voluptatibus sequi cupiditate minus soluta temporibus.',
                'salary' => '£13,000 - £15,000',
                'category_id' => 1,
                'location_id' => 1,
                'closing_date' => (new \DateTime())->modify('+1 day')->format('Y-m-d')
            ]
        ];

        $authentication = $this->authentication;
        $authentication->expects($this->once())
                       ->method('getUser')
                       ->willReturn(new \JosJobs\Entity\User($this->jobsTable));

        $jobController = new \JosJobs\Controllers\Job(
            $authentication,
            $this->applicationsTable,
            $this->categoriesTable,
            $this->jobsTable,
            $this->locationsTable,
            $this->usersTable,
            [],
            $testPostData,
            $this->fileUpload
        );

        $this->assertEquals(
            $jobController->saveUpdate()['template'],
            '/admin/jobs/update.html.php'
        );
    }

    /**
     * Given invalid data, a job is not created and the
     * user is shown the form again with errors.
     */
    public function testSalaryExceedsLength()
    {
        $testPostData = [
            'job' => [
                'title' => 'Sales Assistant',
                'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum illum voluptas sed odio ipsa, illo placeat quo tenetur corporis in sunt? Deserunt quibusdam labore explicabo aut ipsum, dolorum beatae? Sit?',
                'salary' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Fugiat, neque. Modi ipsam deserunt, ex facere voluptas dolorum natus veniam praesentium cupiditate cum saepe illo aut expedita voluptatem dolores. Iusto porro unde optio quidem pariatur, enim tenetur placeat.',
                'category_id' => 1,
                'location_id' => 1,
                'closing_date' => (new \DateTime())->modify('+1 day')->format('Y-m-d')
            ]
        ];

        $authentication = $this->authentication;
        $authentication->expects($this->once())
                       ->method('getUser')
                       ->willReturn(new \JosJobs\Entity\User($this->jobsTable));

        $jobController = new \JosJobs\Controllers\Job(
            $authentication,
            $this->applicationsTable,
            $this->categoriesTable,
            $this->jobsTable,
            $this->locationsTable,
            $this->usersTable,
            [],
            $testPostData,
            $this->fileUpload
        );

        $this->assertEquals(
            $jobController->saveUpdate()['template'],
            '/admin/jobs/update.html.php'
        );
    }

    /**
     * Given invalid data, a job is not created and the
     * user is shown the form again with errors.
     */
    public function testMultipleExceedsLength()
    {
        $testPostData = [
            'job' => [
                'title' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Fugiat, neque. Modi ipsam deserunt, ex facere voluptas dolorum natus veniam praesentium cupiditate cum saepe illo aut expedita voluptatem dolores. Iusto porro unde optio quidem pariatur, enim tenetur placeat.',
                'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum illum voluptas sed odio ipsa, illo placeat quo tenetur corporis in sunt? Deserunt quibusdam labore explicabo aut ipsum, dolorum beatae? Sit?',
                'salary' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Fugiat, neque. Modi ipsam deserunt, ex facere voluptas dolorum natus veniam praesentium cupiditate cum saepe illo aut expedita voluptatem dolores. Iusto porro unde optio quidem pariatur, enim tenetur placeat.',
                'category_id' => 1,
                'location_id' => 1,
                'closing_date' => (new \DateTime())->modify('+1 day')->format('Y-m-d')
            ]
        ];

        $authentication = $this->authentication;
        $authentication->expects($this->once())
                       ->method('getUser')
                       ->willReturn(new \JosJobs\Entity\User($this->jobsTable));

        $jobController = new \JosJobs\Controllers\Job(
            $authentication,
            $this->applicationsTable,
            $this->categoriesTable,
            $this->jobsTable,
            $this->locationsTable,
            $this->usersTable,
            [],
            $testPostData,
            $this->fileUpload
        );

        $this->assertEquals(
            $jobController->saveUpdate()['template'],
            '/admin/jobs/update.html.php'
        );
    }

    /**
     * Given invalid data, a job is not created and the
     * user is shown the form again with errors.
     */
    public function testClosingDateYesterday()
    {
        $testPostData = [
            'job' => [
                'title' => 'Sales Assistant',
                'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum illum voluptas sed odio ipsa, illo placeat quo tenetur corporis in sunt? Deserunt quibusdam labore explicabo aut ipsum, dolorum beatae? Sit?',
                'salary' => '£13,000 - £15,000',
                'category_id' => 1,
                'location_id' => 1,
                'closing_date' => (new \DateTime())->modify('-1 day')->format('Y-m-d')
            ]
        ];

        $authentication = $this->authentication;
        $authentication->expects($this->once())
                       ->method('getUser')
                       ->willReturn(new \JosJobs\Entity\User($this->jobsTable));

        $jobController = new \JosJobs\Controllers\Job(
            $authentication,
            $this->applicationsTable,
            $this->categoriesTable,
            $this->jobsTable,
            $this->locationsTable,
            $this->usersTable,
            [],
            $testPostData,
            $this->fileUpload
        );

        $this->assertEquals(
            $jobController->saveUpdate()['template'],
            '/admin/jobs/update.html.php'
        );
    }

    /**
     * Given invalid data, a job is not created and the
     * user is shown the form again with errors.
     */
    public function testClosingDateNow()
    {
        $testPostData = [
            'job' => [
                'title' => 'Sales Assistant',
                'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum illum voluptas sed odio ipsa, illo placeat quo tenetur corporis in sunt? Deserunt quibusdam labore explicabo aut ipsum, dolorum beatae? Sit?',
                'salary' => '£13,000 - £15,000',
                'category_id' => 1,
                'location_id' => 1,
                'closing_date' => (new \DateTime())->format('Y-m-d')
            ]
        ];

        $authentication = $this->authentication;
        $authentication->expects($this->once())
                       ->method('getUser')
                       ->willReturn(new \JosJobs\Entity\User($this->jobsTable));

        $jobController = new \JosJobs\Controllers\Job(
            $authentication,
            $this->applicationsTable,
            $this->categoriesTable,
            $this->jobsTable,
            $this->locationsTable,
            $this->usersTable,
            [],
            $testPostData,
            $this->fileUpload
        );

        $this->assertEquals(
            $jobController->saveUpdate()['template'],
            '/admin/jobs/update.html.php'
        );
    }

    /**
     * Given invalid data, a job is not created and the
     * user is shown the form again with errors.
     */
    public function testClosingDatePlus366Days()
    {
        $testPostData = [
            'job' => [
                'title' => 'Sales Assistant',
                'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum illum voluptas sed odio ipsa, illo placeat quo tenetur corporis in sunt? Deserunt quibusdam labore explicabo aut ipsum, dolorum beatae? Sit?',
                'salary' => '£13,000 - £15,000',
                'category_id' => 1,
                'location_id' => 1,
                'closing_date' => (new \DateTime())->modify('+1 year +1 day')->format('Y-m-d')
            ]
        ];

        $authentication = $this->authentication;
        $authentication->expects($this->once())
                       ->method('getUser')
                       ->willReturn(new \JosJobs\Entity\User($this->jobsTable));

        $jobController = new \JosJobs\Controllers\Job(
            $authentication,
            $this->applicationsTable,
            $this->categoriesTable,
            $this->jobsTable,
            $this->locationsTable,
            $this->usersTable,
            [],
            $testPostData,
            $this->fileUpload
        );

        $this->assertEquals(
            $jobController->saveUpdate()['template'],
            '/admin/jobs/update.html.php'
        );
    }
}
