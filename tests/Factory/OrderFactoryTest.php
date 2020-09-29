<?php

namespace Dynamic\Foxy\Orders\Tests\Factory;

use Dynamic\Foxy\Extension\Purchasable;
use Dynamic\Foxy\Model\FoxyHelper;
use Dynamic\Foxy\Model\Variation;
use Dynamic\Foxy\Orders\Model\Order;
use Dynamic\Foxy\Orders\Tests\TestOnly\Controller\FoxyDataTestController;
use Dynamic\Foxy\Orders\Tests\TestOnly\Extension\TestVariationDataExtension;
use Dynamic\Foxy\Orders\Tests\TestOnly\Page\TestProduct;
use Dynamic\Foxy\Parser\Controller\FoxyController;
use SilverStripe\Control\Director;
use SilverStripe\Dev\FunctionalTest;

/**
 * Class OrderFactoryTest
 * @package Dynamic\Foxy\Orders\Tests\Factory
 */
class OrderFactoryTest extends FunctionalTest
{
    const DUMMY_KEY = 'abc123xzy';

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
     * @var string[]
     */
    protected static $extra_controllers = [
        FoxyDataTestController::class,
    ];

    /**
     *
     */
    protected function setUp()
    {
        Director::config()->merge('rules', ['foxy//$Action/$ID/$Name' => FoxyController::class,]);

        parent::setUp();
    }

    /**
     *
     */
    public function testGetOrder()
    {
        $currentKey = FoxyHelper::config()->get('secret');
        FoxyHelper::config()->set('secret', static::DUMMY_KEY);

        $payLoadResponse = $this->get('/foxytestdata.xml');
        $this->post('/foxy', ['FoxyData' => $payLoadResponse->getBody()]);

        $order = Order::get()->filter('OrderID', 111111222222333333)->first();

        $this->assertInstanceOf(Order::class, $order);

        FoxyHelper::config()->set('secret', $currentKey);
    }
}
