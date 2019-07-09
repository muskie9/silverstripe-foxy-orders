<?php

namespace Dynamic\Foxy\Orders\Test\Model;

use Dynamic\Foxy\Extension\Purchasable;
use Dynamic\Foxy\Orders\Model\Order;
use Dynamic\Foxy\Test\TestOnly\TestProduct;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\Forms\FieldList;
use SilverStripe\i18n\i18n;
use SilverStripe\Security\Member;

class OrderTest extends SapphireTest
{
    /**
     * @var string
     */
    protected static $fixture_file = '../fixtures.yml';

    /**
     *
     */
    public function testGetCMSFields()
    {
        $object = $this->objFromFixture(Order::class, 'one');
        $fields = $object->getCMSFields();
        $this->assertInstanceOf(FieldList::class, $fields);
    }

    /**
     *
     */
    public function testProvidePermissions()
    {
        /** @var TestProduct $object */
        $object = singleton(Order::class);

        i18n::set_locale('en');
        $expected = [
            'MANAGE_FOXY_ORDERS' => [
                'name' => 'Manage orders',
                'category' => 'Foxy',
                'help' => 'Manage orders and view recipts',
                'sort' => 400
            ]
        ];
        $this->assertEquals($expected, $object->providePermissions());
    }

    /**
     *
     */
    public function testCanCreate()
    {
        /** @var Order $object */
        $object = singleton(Order::class);
        /** @var \SilverStripe\Security\Member $admin */
        $admin = $this->objFromFixture(Member::class, 'admin');
        /** @var \SilverStripe\Security\Member $siteOwner */
        $siteOwner = $this->objFromFixture(Member::class, 'site-owner');
        /** @var \SilverStripe\Security\Member $default */
        $default = $this->objFromFixture(Member::class, 'default');

        $this->assertFalse($object->canCreate($default));
        $this->assertFalse($object->canCreate($admin));
        $this->assertFalse($object->canCreate($siteOwner));
    }

    /**
     *
     */
    public function testCanEdit()
    {
        /** @var Order $object */
        $object = singleton(Order::class);
        /** @var \SilverStripe\Security\Member $admin */
        $admin = $this->objFromFixture(Member::class, 'admin');
        /** @var \SilverStripe\Security\Member $siteOwner */
        $siteOwner = $this->objFromFixture(Member::class, 'site-owner');
        /** @var \SilverStripe\Security\Member $default */
        $default = $this->objFromFixture(Member::class, 'default');

        $this->assertFalse($object->canEdit($default));
        $this->assertFalse($object->canEdit($admin));
        $this->assertFalse($object->canEdit($siteOwner));
    }

    /**
     *
     */
    public function testCanDelete()
    {
        /** @var Order $object */
        $object = singleton(Order::class);
        /** @var \SilverStripe\Security\Member $admin */
        $admin = $this->objFromFixture(Member::class, 'admin');
        /** @var \SilverStripe\Security\Member $siteOwner */
        $siteOwner = $this->objFromFixture(Member::class, 'site-owner');
        /** @var \SilverStripe\Security\Member $default */
        $default = $this->objFromFixture(Member::class, 'default');

        $this->assertFalse($object->canDelete($default));
        $this->assertFalse($object->canDelete($admin));
        $this->assertFalse($object->canDelete($siteOwner));
    }
}
