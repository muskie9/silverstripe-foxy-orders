<?php

namespace Dynamic\Foxy\Orders\Model;

use SilverStripe\ORM\DataObject;

/**
 * Class OrderDiscount
 * @package Dynamic\Foxy\Orders\Model
 *
 * @property string $Title
 * @property string $Code
 * @property int $Amount
 * @property string $Display
 * @property string $DiscountType
 * @property string $DiscountDetails
 * @property int $OrderID
 *
 * @method Order Order()
 */
class OrderDiscount extends DataObject
{
    /**
     * @var string
     */
    private static $table_name = 'OrderDiscount';

    /**
     * @var string
     */
    private static $singular_name = 'Order Discount';

    /**
     * @var string
     */
    private static $plural_name = 'Order Discounts';

    /**
     * @var array
     */
    private static $db = [
        'Title' => 'Varchar(255)',
        'Code' => 'Varchar(255)',
        'Amount' => 'Int',
        'Display' => 'Varchar',
        'DiscountType' => 'Varchar',
        'DiscountDetails' => 'Varchar',
    ];

    /**
     * @var array
     */
    private static $has_one = [
        'Order' => Order::class,
    ];
}
