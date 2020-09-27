<?php

namespace Dynamic\Foxy\Orders\Tests\Extension;

use Dynamic\Foxy\Orders\Extension\MemberDataExtension;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Security\Member;

/**
 * Class MemberDataExtensionTest
 * @package Dynamic\Foxy\Orders\Tests\Extension
 */
class MemberDataExtensionTest extends SapphireTest
{
    /**
     * @var string[]
     */
    protected static $fixture_file = [
        '../customers.yml',
    ];

    /**
     * @var \string[][]
     */
    protected static $required_extensions = [
        Member::class => [
            MemberDataExtension::class,
        ],
    ];

    /**
     *
     */
    public function testHasExtensionApplied()
    {
        $member = Member::singleton();
        $this->assertTrue($member->hasExtension(MemberDataExtension::class));
    }

    /**
     *
     */
    public function testOrdersField()
    {
        $member = Member::singleton();
        $this->assertNull($member->getCMSFields()->dataFieldByName('Orders'));

        /** @var Member $customer */
        $customer = $this->objFromFixture(Member::class, 'customerone');
        $this->assertInstanceOf(
            GridField::class,
            $customer->getCMSFields()->dataFieldByName('Orders')
        );
    }
}
