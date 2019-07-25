<?php

namespace Dynamic\Foxy\Orders\Test\Page;

use Dynamic\Foxy\Orders\Page\OrderHistory;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\Forms\FieldList;

class OrderHistoryTest extends SapphireTest
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
        $object = $this->objFromFixture(OrderHistory::class, 'one');
        $fields = $object->getCMSFields();
        $this->assertInstanceOf(FieldList::class, $fields);
    }
}
