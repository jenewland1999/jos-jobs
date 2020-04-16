<?php

namespace JosJobs\Tests;

class CreateEnquiryTest extends \PHPUnit\Framework\TestCase
{
    private $authentication;
    private $enquiriesTable;
    private $usersTable;

    public function setUp()
    {
        $this->authentication = $this->createMock(\CupOfPHP\Authentication::class);
        $this->enquiriesTable = $this->createMock(\CupOfPHP\DatabaseTable::class);
        $this->usersTable = $this->createMock(\CupOfPHP\DatabaseTable::class);
    }

    /**
     * Given valid data, an enquiry is created and the
     * user is redirected to the same page with query
     * string of reply=success.
     *
     * @runInSeparateProcess
     */
    public function testValid()
    {
        $testPostData = [
            'enquiry' => [
                'name' => 'John Smith',
                'email' => 'john.smith@example.org',
                'tel_no' => '+447000000000',
                'enquiry' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolores, laudantium?'
            ]
        ];

        $enquiriesTable = $this->enquiriesTable;
        $enquiriesTable->expects($this->once())
                        ->method('insert')
                        ->with(
                            $this->equalTo($testPostData['enquiry'])
                        );

        $enquiryController = new \JosJobs\Controllers\Enquiry(
            $this->authentication,
            $enquiriesTable,
            $this->usersTable,
            [],
            $testPostData
        );

        $this->assertEquals($enquiryController->create(), 302);
    }

    /**
     * Given invalid/missing data, the user is shown
     * the form again with errors.
     */
    public function testNullAll()
    {
        $testPostData = [
            'enquiry' => [
                'name' => '',
                'email' => '',
                'tel_no' => '',
                'enquiry' => ''
            ]
        ];

        $enquiryController = new \JosJobs\Controllers\Enquiry(
            $this->authentication,
            $this->enquiriesTable,
            $this->usersTable,
            [],
            $testPostData
        );

        $this->assertEquals($enquiryController->create()['template'], '/contact/index.html.php');
    }

    /**
     * Given invalid/missing data, the user is shown
     * the form again with errors.
     */
    public function testNullName()
    {
        $testPostData = [
            'enquiry' => [
                'name' => '',
                'email' => 'john.smith@example.org',
                'tel_no' => '+447000000000',
                'enquiry' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolores, laudantium?'
            ]
        ];

        $enquiryController = new \JosJobs\Controllers\Enquiry(
            $this->authentication,
            $this->enquiriesTable,
            $this->usersTable,
            [],
            $testPostData
        );

        $this->assertEquals($enquiryController->create()['template'], '/contact/index.html.php');
    }

    /**
     * Given invalid/missing data, the user is shown
     * the form again with errors.
     */
    public function testNullEmail()
    {
        $testPostData = [
            'enquiry' => [
                'name' => 'John Smith',
                'email' => '',
                'tel_no' => '+447000000000',
                'enquiry' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolores, laudantium?'
            ]
        ];

        $enquiryController = new \JosJobs\Controllers\Enquiry(
            $this->authentication,
            $this->enquiriesTable,
            $this->usersTable,
            [],
            $testPostData
        );

        $this->assertEquals($enquiryController->create()['template'], '/contact/index.html.php');
    }

    /**
     * Given invalid/missing data, the user is shown
     * the form again with errors.
     */
    public function testNullTelNo()
    {
        $testPostData = [
            'enquiry' => [
                'name' => 'John Smith',
                'email' => 'john.smith@example.org',
                'tel_no' => '',
                'enquiry' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolores, laudantium?'
            ]
        ];

        $enquiryController = new \JosJobs\Controllers\Enquiry(
            $this->authentication,
            $this->enquiriesTable,
            $this->usersTable,
            [],
            $testPostData
        );

        $this->assertEquals($enquiryController->create()['template'], '/contact/index.html.php');
    }

    /**
     * Given invalid/missing data, the user is shown
     * the form again with errors.
     */
    public function testNullEnquiry()
    {
        $testPostData = [
            'enquiry' => [
                'name' => 'John Smith',
                'email' => 'john.smith@example.org',
                'tel_no' => '+447000000000',
                'enquiry' => ''
            ]
        ];

        $enquiryController = new \JosJobs\Controllers\Enquiry(
            $this->authentication,
            $this->enquiriesTable,
            $this->usersTable,
            [],
            $testPostData
        );

        $this->assertEquals($enquiryController->create()['template'], '/contact/index.html.php');
    }

    /**
     * Given invalid/missing data, the user is shown
     * the form again with errors.
     */
    public function testNullMultiple()
    {
        $testPostData = [
            'enquiry' => [
                'name' => '',
                'email' => 'john.smith@example.org',
                'tel_no' => '+447000000000',
                'enquiry' => ''
            ]
        ];

        $enquiryController = new \JosJobs\Controllers\Enquiry(
            $this->authentication,
            $this->enquiriesTable,
            $this->usersTable,
            [],
            $testPostData
        );

        $this->assertEquals($enquiryController->create()['template'], '/contact/index.html.php');
    }

    /**
     * Given invalid/missing data, the user is shown
     * the form again with errors.
     */
    public function testNameExceedsLength()
    {
        $testPostData = [
            'enquiry' => [
                'name' => 'Lorem ipsum dolor sit amet consectetur, adipisicing elit. Ut modi ducimus nam fugiat nostrum a quaerat, facilis et temporibus nulla hic rerum. Voluptates similique expedita veritatis illum, ipsam officia corrupti mollitia. Possimus dolorum eaque iusto doloribus sit itaque atque facere!',
                'email' => 'john.smith@example.org',
                'tel_no' => '+447000000000',
                'enquiry' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolores, laudantium?'
            ]
        ];

        $enquiryController = new \JosJobs\Controllers\Enquiry(
            $this->authentication,
            $this->enquiriesTable,
            $this->usersTable,
            [],
            $testPostData
        );

        $this->assertEquals($enquiryController->create()['template'], '/contact/index.html.php');
    }

    /**
     * Given invalid/missing data, the user is shown
     * the form again with errors.
     */
    public function testEmailExceedsLength()
    {
        $testPostData = [
            'enquiry' => [
                'name' => 'John Smith',
                'email' => 'testtesttesttesttesttesttesttesttesttesttest.testtesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttest@testtesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttest.co.uk',
                'tel_no' => '+447000000000',
                'enquiry' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolores, laudantium?'
            ]
        ];

        $enquiryController = new \JosJobs\Controllers\Enquiry(
            $this->authentication,
            $this->enquiriesTable,
            $this->usersTable,
            [],
            $testPostData
        );

        $this->assertEquals($enquiryController->create()['template'], '/contact/index.html.php');
    }

    /**
     * Given invalid/missing data, the user is shown
     * the form again with errors.
     */
    public function testEnquiryExceedsLength()
    {
        $testPostData = [
            'enquiry' => [
                'name' => 'John Smith',
                'email' => 'john.smith@example.org',
                'tel_no' => '+447000000000',
                'enquiry' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Aut rem facilis illo totam expedita aspernatur nemo sit cumque consectetur, ratione placeat voluptatum ipsa deleniti sed harum, reiciendis neque hic ea, earum natus veritatis aperiam iure maiores odio! Dolore vero magni, placeat corrupti fugiat consectetur laborum optio minima dolor nesciunt animi nam, culpa asperiores voluptate neque reiciendis explicabo quos tempora sint. Incidunt provident laborum quibusdam, culpa iste sint possimus temporibus voluptate aliquid ducimus repellendus corporis aspernatur sed illo at eveniet dolor minima tempora labore natus? Suscipit illum maiores nesciunt alias nisi fugit laudantium architecto soluta ab porro iste deleniti doloremque, accusantium, est voluptatibus obcaecati accusamus numquam perspiciatis tempora odio at. Nobis earum, eius cum perspiciatis quo culpa saepe reprehenderit, hic iure dolores iusto minima amet eos architecto! Corrupti vitae quae pariatur voluptatibus modi? Quisquam natus reprehenderit architecto molestias, doloribus ab fuga rem optio hic nisi velit ea reiciendis consequuntur nihil? Ratione, minus. Doloribus itaque minus delectus. Repudiandae libero saepe, id maxime fuga aperiam dignissimos, velit nulla corrupti rem nemo, doloremque iusto illum quod. Nulla, officia. Commodi magnam natus aperiam, in ut necessitatibus unde eveniet nemo autem quaerat inventore at! Magnam fugiat libero, nam excepturi quos id assumenda minus sed deleniti nisi itaque tempore accusamus eos enim voluptate quisquam dignissimos, eius vero commodi sint alias necessitatibus ipsam ad aliquid! Eius quae nihil sapiente placeat facilis saepe optio voluptatibus non repudiandae repellendus cum maxime et vero inventore, deleniti quod vitae ipsam in praesentium beatae minus eaque? Aperiam odit ut ipsam alias tempore culpa. Quaerat esse vitae consequatur nam, repellendus doloribus voluptatem, veritatis quae aliquam odit, reprehenderit perspiciatis. Quibusdam accusantium aperiam sint nesciunt voluptatum, voluptatibus excepturi quas eligendi adipisci pariatur tempore. Molestiae facilis enim mollitia voluptates eligendi sapiente dolorum vero exercitationem reiciendis velit aliquam quo similique laudantium ad aut, sit, dolores sint fugiat! A provident temporibus, id, animi nam ex magnam modi nesciunt quia eius autem numquam saepe laborum nisi? Voluptates ex aspernatur quod eos quasi. Fugiat, unde laudantium! Non ratione ut accusamus, modi suscipit repellendus numquam voluptate error iste alias similique veritatis optio maxime consequatur laborum quas sapiente veniam at fuga voluptas harum nam quos natus qui! Velit tempora nam facere odio, ducimus saepe debitis quisquam corrupti ab commodi! Enim, sunt. Dolore vero eum accusamus aliquid! Quod inventore doloremque iusto quaerat alias? Error, magnam. Excepturi eaque alias impedit vitae? Sequi dolorum doloremque, qui quia nihil beatae sunt ducimus earum autem saepe accusantium minus tempore natus aperiam voluptas temporibus soluta quo consequuntur quod error quos fugiat fugit aut id! Earum adipisci, incidunt totam magni doloremque animi soluta ratione rerum impedit consequatur asperiores repudiandae perspiciatis veniam aliquid eum, saepe laboriosam illum? Repudiandae delectus eum, itaque dolores nihil dolor quis nobis voluptatem aut laborum ipsa deserunt quam sed! Blanditiis, nostrum fugiat ipsum autem inventore harum similique nobis odit quo ex, cupiditate voluptatem tempore magnam commodi consectetur aliquid. Necessitatibus hic nobis ullam nulla sit corrupti, minus culpa dolores voluptatum accusamus voluptate porro voluptatem eveniet et labore mollitia quam consequuntur non cumque illum earum recusandae ipsa provident eius? Corrupti, vitae beatae reiciendis commodi optio adipisci ea nulla. Odit, nihil, nostrum enim fuga eius laudantium assumenda aperiam reprehenderit ullam ea dolorum recusandae numquam omnis iure! Numquam provident quo quibusdam, quisquam sequi nostrum neque sit aliquam nemo perspiciatis ea cumque qui voluptates esse minima soluta. Eius culpa, earum eligendi cupiditate fugiat mollitia dolore dolorem nisi officiis, voluptatibus recusandae animi perferendis placeat est, natus hic molestias. Odit ipsam dolorum deleniti minima earum quibusdam! Qui consequatur minima consectetur quas quisquam expedita delectus voluptate voluptatem ipsum fugiat numquam quibusdam incidunt, ad nobis at deserunt saepe deleniti, vitae voluptatibus nam laudantium optio. Ipsa, reiciendis aspernatur. Non, iste eum, obcaecati inventore perferendis quisquam, suscipit in accusamus expedita nostrum et adipisci. Nesciunt rem enim at voluptatibus? Consectetur reiciendis neque, necessitatibus quaerat delectus modi dolorem fuga minima eligendi explicabo expedita inventore adipisci itaque voluptate similique laboriosam corporis culpa ex velit cum nulla repudiandae doloremque ullam. Rerum, accusamus numquam? Ex temporibus sunt eligendi vel labore excepturi. Odio distinctio fuga veritatis deserunt laboriosam! Iste sunt sed sequi aliquam consectetur, a blanditiis qui consequuntur debitis fugiat, ipsa quam quod nostrum inventore tempora nulla maxime! Laborum, animi iusto. Expedita dolorem nostrum blanditiis fuga doloremque hic provident inventore ipsa explicabo quos voluptates tempora, accusamus libero molestiae aliquam vero voluptas nam vitae placeat ducimus? At in cum laborum veritatis neque autem aliquam corrupti, dolores, quaerat expedita fugiat velit assumenda officiis maiores, sequi reprehenderit debitis eius optio amet! Dolor animi soluta impedit aliquid beatae blanditiis consequuntur quasi nobis autem hic totam cum, molestiae voluptatibus et libero debitis ad doloribus quis, quae fugit nam optio eos! Quam corrupti odit nobis quos sapiente incidunt fugit velit dicta consequuntur cupiditate at, animi hic repellendus eos, facilis quas inventore neque saepe similique officia quisquam error? In dolores qui, at necessitatibus voluptatum eos neque architecto similique quaerat veniam quidem nostrum exercitationem repellat. Deserunt accusantium veniam nisi quidem ipsum totam tenetur eos et sint temporibus, molestias, officia maiores rerum aliquid, harum sit sapiente omnis. Repellat ea minus, sequi incidunt tempore maxime, temporibus voluptatibus, officiis non numquam possimus! Incidunt, nulla non unde illum repellendus laboriosam cumque quisquam quos, voluptate perferendis quaerat pariatur sit libero possimus natus ex eveniet officiis rerum. Adipisci tempore, quas, itaque, ut placeat sed qui dolorem nam iure rem veniam mollitia aperiam fugiat expedita incidunt reprehenderit minus repellat? Est pariatur, laborum quis aspernatur ex veniam architecto deserunt dolore illo delectus molestias, quidem dignissimos voluptas numquam tempore fugit quibusdam quaerat! Officia provident dolorum temporibus eaque cupiditate velit, minus aliquam ducimus consequatur iure consequuntur eligendi et voluptatum quam animi quaerat delectus error placeat nemo aut rem deleniti tempore quas? Laborum repellendus harum, nobis maiores, aperiam perspiciatis repellat velit officia illo mollitia nulla ex ut, et sequi eligendi assumenda? Ea, ex id, voluptas corrupti commodi neque vel consequuntur voluptatum pariatur adipisci laudantium numquam. Voluptate ipsam excepturi similique provident doloremque accusantium nemo, velit saepe! Rem error culpa officiis illum inventore sequi sunt aliquam fugiat ea vitae, iusto possimus laborum voluptate. Expedita, consectetur id totam enim numquam quae fugiat itaque amet et recusandae minus! Ullam, esse inventore, officia eos corporis, recusandae exercitationem est voluptas consequuntur ad reiciendis. Reiciendis atque harum culpa exercitationem sunt consequatur aliquid praesentium, ea, molestias natus, libero suscipit. Eos, praesentium adipisci? Ratione ad soluta sed, excepturi distinctio iure ipsa quod, tenetur nesciunt natus voluptas animi! Provident error asperiores magnam ipsa saepe! Quo amet magni, iusto eum ex fugiat exercitationem qui quisquam ad distinctio optio saepe fugit. In fugiat quaerat dolores nihil, impedit vero quasi ut cumque minus alias minima totam natus sapiente corrupti esse voluptas autem porro facere ipsum ex cum sit! Laborum, dolorem molestiae libero saepe, vel quidem aliquid eveniet ipsam fugiat quod, nulla dolore quam sed. Modi quas veritatis ullam illum, doloribus, asperiores enim odit error esse totam unde distinctio vitae sint magnam laborum suscipit non similique, animi inventore illo fugit itaque. Laborum minus optio eligendi, cumque magnam est vero ut expedita deleniti quisquam labore atque natus error aliquam reiciendis dolorum nihil hic culpa? Reprehenderit dolorem deleniti maiores iusto ad, id magnam fugiat ipsa molestias ea libero repudiandae quam, minus commodi veniam temporibus iste saepe. Earum voluptate quis facilis a odio quas iste tenetur! Ab accusamus enim tempora autem quis debitis ducimus iusto nemo temporibus, iure commodi minima?'
            ]
        ];

        $enquiryController = new \JosJobs\Controllers\Enquiry(
            $this->authentication,
            $this->enquiriesTable,
            $this->usersTable,
            [],
            $testPostData
        );

        $this->assertEquals($enquiryController->create()['template'], '/contact/index.html.php');
    }

    /**
     * Given invalid/missing data, the user is shown
     * the form again with errors.
     */
    public function testEmailInvalidFmt()
    {
        $testPostData = [
            'enquiry' => [
                'name' => 'John Smith',
                'email' => 'john.smith[at]example[dot]org',
                'tel_no' => '+447000000000',
                'enquiry' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolores, laudantium?'
            ]
        ];

        $enquiryController = new \JosJobs\Controllers\Enquiry(
            $this->authentication,
            $this->enquiriesTable,
            $this->usersTable,
            [],
            $testPostData
        );

        $this->assertEquals($enquiryController->create()['template'], '/contact/index.html.php');
    }

    /**
     * Given invalid/missing data, the user is shown
     * the form again with errors.
     */
    public function testTelNoInvalidFmt()
    {
        $testPostData = [
            'enquiry' => [
                'name' => 'John Smith',
                'email' => 'john.smith@example.org',
                'tel_no' => 'zero-eight-hundred 353 353',
                'enquiry' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolores, laudantium?'
            ]
        ];

        $enquiryController = new \JosJobs\Controllers\Enquiry(
            $this->authentication,
            $this->enquiriesTable,
            $this->usersTable,
            [],
            $testPostData
        );

        $this->assertEquals($enquiryController->create()['template'], '/contact/index.html.php');
    }

    /**
     * Given invalid/missing data, the user is shown
     * the form again with errors.
     */
    public function testInvalidFmtMultiple()
    {
        $testPostData = [
            'enquiry' => [
                'name' => 'John Smith',
                'email' => 'john.smith[at]example[dot]org',
                'tel_no' => 'zero-eight-hundred 353 353',
                'enquiry' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolores, laudantium?'
            ]
        ];

        $enquiryController = new \JosJobs\Controllers\Enquiry(
            $this->authentication,
            $this->enquiriesTable,
            $this->usersTable,
            [],
            $testPostData
        );

        $this->assertEquals($enquiryController->create()['template'], '/contact/index.html.php');
    }
}
