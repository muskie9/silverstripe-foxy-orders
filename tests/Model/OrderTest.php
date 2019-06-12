<?php

namespace Dynamic\Foxy\Test\Model;

use Dynamic\Foxy\Model\Order;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\Forms\FieldList;

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
}
