<?php

namespace Dynamic\Foxy\Orders\Tests\Factory;

use Dynamic\Foxy\Extension\Purchasable;
use Dynamic\Foxy\Model\FoxyHelper;
use Dynamic\Foxy\Model\Variation;
use Dynamic\Foxy\Orders\Model\Order;
use Dynamic\Foxy\Orders\Tests\TestOnly\Extension\TestVariationDataExtension;
use Dynamic\Foxy\Orders\Tests\TestOnly\Page\TestProduct;
use Dynamic\Foxy\Parser\Controller\FoxyController;
use SilverStripe\Control\Controller;
use SilverStripe\Control\Director;
use SilverStripe\Dev\FunctionalTest;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\FieldType\DBHTMLText;
use SilverStripe\Security\PasswordEncryptor;
use SilverStripe\Security\PasswordEncryptor_NotFoundException;
use SilverStripe\View\ArrayData;

class OrderFactoryTest extends FunctionalTest
{
    /**
     * @var string
     */
    private $dummy_key = 'abc123xzy';

    /**
     * @var string[]
     */
    protected static $fixture_file = [
        '../orders.yml',
        '../orderhistory.yml',
        '../customers.yml',
    ];

    /**
     * @var string[]
     */
    protected static $extra_dataobjects = [
        TestProduct::class,
    ];

    /**
     * @var \string[][]
     */
    protected static $required_extensions = [
        Variation::class => [
            TestVariationDataExtension::class,
        ],
        TestProduct::class => [
            Purchasable::class,
        ],
    ];

    /**
     *
     */
    protected function setUp()
    {
        Director::config()->merge('rules', ['foxy//$Action/$ID/$Name' => FoxyController::class]);

        parent::setUp();
    }

    /**
     * @return string
     * @throws PasswordEncryptor_NotFoundException
     */
    protected function getTransactionData()
    {
        return \rc4crypt::encrypt($this->dummy_key, $this->getXML()->getValue());
    }

    /**
     * @return DBHTMLText
     * @throws PasswordEncryptor_NotFoundException
     */
    protected function getXML()
    {
        return Controller::singleton()->renderWith(
            'TestData',
            [
                'OrderID' => 111111222222333333,
                'TransactionDate' => strtotime('now'),
                'Email' => 'test@test.test',
                'HashType' => 'sha1_salted_suffix',
                'Salt' => 'faGgWXUTdZ7i42lpA6cljzKeGBeUwShBSNHECwsJmt',
                'HashedPassword' => PasswordEncryptor::create_for_algorithm('sha1_v2.4')->encrypt('MyF00p@$$w0Rd', 'faGgWXUTdZ7i42lpA6cljzKeGBeUwShBSNHECwsJmt'),
                'OrderDetails' => $this->getOrderDetails(),
            ]
        );
    }

    /**
     * @return ArrayList
     */
    protected function getOrderDetails()
    {
        return ArrayList::create([
            ArrayData::create([
                'Options' => $this->getOptions(),
            ]),
        ]);
    }

    /**
     * @return ArrayList
     */
    protected function getOptions()
    {
        return ArrayList::create();
    }

    /**
     * @throws PasswordEncryptor_NotFoundException
     */
    public function testGetOrder()
    {
        $currentKey = FoxyHelper::config()->get('secret');
        FoxyHelper::config()->set('secret', $this->dummy_key);
        $xmlString = <<<XML
{$this->getTransactionData()}
XML;


        $this->post('/foxy', ['FoxyData' => $xmlString], ['Content-Type' => 'application/octet-stream']);

        $order = Order::get()->filter('OrderID', 111111222222333333)->first();

        $this->assertInstanceOf(Order::class, $order);

        FoxyHelper::config()->set('secret', $currentKey);
    }
}
