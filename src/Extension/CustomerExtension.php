<?php

namespace Dynamic\Foxy\Orders\Extension;

use Dynamic\Foxy\Orders\Model\Order;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\DataExtension;

class CustomerExtension extends DataExtension
{
    /**
     * @var array
     */
    private static $db = [
        'Customer_ID' => 'Int', // ID from Foxy system
    ];

    /**
     * @var array
     */
    private static $has_many = [
        'Orders' => Order::class,
    ];

    /**
     * @var array
     */
    private static $indexes = [
        'Customer_ID' => true, // make unique
    ];

    /**
     * @param FieldList $fields
     */
    public function updateCMSFields(FieldList $fields)
    {
        $fields->replaceField(
            'Customer_ID',
            TextField::create('Customer_ID')->performReadonlyTransformation()
        );
    }
}
