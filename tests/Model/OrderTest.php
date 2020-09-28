<?php

namespace Dynamic\Foxy\Orders\Test\Model;

use Dynamic\Foxy\Extension\Purchasable;
use Dynamic\Foxy\Model\Variation;
use Dynamic\Foxy\Orders\Model\Order;
use Dynamic\Foxy\Orders\Tests\TestOnly\Extension\TestVariationDataExtension;
use Dynamic\Foxy\Orders\Tests\TestOnly\Page\TestProduct;
use SilverStripe\Dev\SapphireTest;

/**
 * Class OrderTest
 * @package Dynamic\Foxy\Orders\Test\Model
 */
class OrderTest extends SapphireTest
{
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
    public function testGetCMSFields()
    {
        $order = $this->objFromFixture(Order::class, 'one');
        $this->assertNull($order->getCMSFields()->dataFieldByName('Response'));
    }

    /**
     *
     */
    public function testReceiptLink()
    {
        /** @var Order $order */
        $order = $this->objFromFixture(Order::class, 'one');

        $this->assertContains(
            'target="_blank" class="cms-panel-link action external-link">view</a>',
            $order->ReceiptLink()
        );
    }

    /**
     *
     */
    public function testGetReceiptLink()
    {
        /** @var Order $order */
        $order = $this->objFromFixture(Order::class, 'one');

        $this->assertContains(
            'target="_blank" class="cms-panel-link action external-link">view</a>',
            $order->getReceiptLink()
        );
    }
}
