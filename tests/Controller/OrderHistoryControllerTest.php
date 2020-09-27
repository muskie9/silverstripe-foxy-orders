<?php

namespace Dynamic\Foxy\Orders\Tests\Controller;

use Dynamic\Foxy\Extension\Purchasable;
use Dynamic\Foxy\Model\Variation;
use Dynamic\Foxy\Orders\Model\Order;
use Dynamic\Foxy\Orders\Page\OrderHistory;
use Dynamic\Foxy\Orders\Page\OrderHistoryController;
use Dynamic\Foxy\Orders\Tests\TestOnly\Extension\TestVariationDataExtension;
use Dynamic\Foxy\Orders\Tests\TestOnly\Page\TestProduct;
use SilverStripe\Dev\FunctionalTest;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\PaginatedList;
use SilverStripe\ORM\ValidationException;
use SilverStripe\Security\Member;
use SilverStripe\Versioned\Versioned;

/**
 * Class OrderHistoryControllerTest
 * @package Dynamic\Foxy\Orders\Tests\Controller
 */
class OrderHistoryControllerTest extends FunctionalTest
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
     * @throws ValidationException
     */
    protected function setUp()
    {
        parent::setUp();

        /*$factory = Injector::inst()->create(FixtureFactory::class);

        $blueprint = Injector::inst()->create(FixtureBlueprint::class, OrderHistory::class);

        $blueprint->addCallback('afterCreate', function ($obj, $identifier, $data, $fixtures) {
            $obj->copyVersionToStage(Versioned::DRAFT, Versioned::LIVE);
        });

        $factory->define('OrderHistory', $blueprint);//*/

        /** @var OrderHistory $page */
        $page = $this->objFromFixture(OrderHistory::class, 'one');
        $page->copyVersionToStage(Versioned::DRAFT, Versioned::LIVE);

        /** @var Order $order */
        $order = $this->objFromFixture(Order::class, 'one');
        /** @var Member $customer */
        $customer = $this->idFromFixture(Member::class, 'customerone');

        $order->MemberID = $customer;
        $order->write();
    }

    /**
     *
     */
    public function testTestOrderHistoryControllerPermission()
    {
        $this->logOut();
        /** @var OrderHistory $page */
        $page = $this->objFromFixture(OrderHistory::class, 'one');
        $controller = OrderHistoryController::create($page);
        $ordersPage = $this->get($controller->Link());

        $this->assertContains('Please login to view this page.', $ordersPage->getBody());

        /** @var Member $customer */
        $customer = $this->objFromFixture(Member::class, 'customerone');
        $this->logInAs($customer);

        $newOrdersPage = $this->get($controller->Link());
        $this->assertNotContains('Please login to view this page.', $newOrdersPage->getBody());
    }

    /**
     *
     */
    public function testOrderPaginatedList()
    {
        $this->logOut();
        /** @var OrderHistory $page */
        $page = $this->objFromFixture(OrderHistory::class, 'one');
        $controller = OrderHistoryController::create($page);

        $this->assertInstanceOf(ArrayList::class, $controller->OrderPaginatedList());

        $customer = $this->objFromFixture(Member::class, 'customerone');
        $this->logInAs($customer);

        $newController = OrderHistoryController::create($page);

        $this->assertInstanceOf(PaginatedList::class, $newController->OrderPaginatedList());
    }
}
