<?php

namespace Dynamic\Foxy\Orders\Extension;

use Dynamic\Foxy\Orders\Model\Order;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\DataExtension;

class MemberDataExtension extends DataExtension
{
    /**
     * @var array
     */
    private static $has_many = [
        'Orders' => Order::class,
    ];

    /**
     * @param FieldList $fields
     */
    public function updateCMSFields(FieldList $fields)
    {
        $fields->replaceField('Customer_ID', TextField::create('Customer_ID')->performReadonlyTransformation());
    }
}
