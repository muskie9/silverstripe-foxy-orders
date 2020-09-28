<?php

namespace Dynamic\Foxy\Orders\Extension;

use Dynamic\Foxy\Orders\Model\Order;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\DataExtension;

/**
 * Class MemberDataExtension
 * @package Dynamic\Foxy\Orders\Extension
 */
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
     *
     * TODO determine where this needs to be moved to.
     * This following assumes this field exists, however the field is not applied by this module.
     */
    public function updateCMSFields(FieldList $fields)
    {
        $fields->replaceField('Customer_ID', TextField::create('Customer_ID')->performReadonlyTransformation());
    }
}
