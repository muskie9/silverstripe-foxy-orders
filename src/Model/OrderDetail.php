<?php

namespace Dynamic\Foxy\Orders\Model;

use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\FieldType\DBCurrency;
use SilverStripe\ORM\HasManyList;

/**
 * Class OrderDetail
 * @package Dynamic\Foxy\Orders\Model
 *
 * @property int $Quantity
 * @property DBCurrency $Price
 * @property string $ProductName
 * @property string $ProductCode
 * @property string $ProductImage
 * @property string $ProductCategory
 * @property int $ProductID
 * @property int $OrderID
 *
 * @method SiteTree Product
 * @method Order Order
 *
 * @method HasManyList OrderOptions
 */
class OrderDetail extends DataObject
{
    /**
     * @var array
     */
    private static $db = [
        'Quantity' => 'Int',
        'Price' => 'Currency',
        'ProductName' => 'Varchar(255)',
        'ProductCode' => 'Varchar(100)',
        'ProductImage' => 'Text',
        'ProductCategory' => 'Varchar(100)',
    ];

    /**
     * @var array
     */
    private static $has_one = [
        'Product' => SiteTree::class,
        'Order' => Order::class,
    ];

    /**
     * @var array
     */
    private static $has_many = [
        'OrderOptions' => OrderOption::class,
    ];

    /**
     * @var array
     */
    private static $summary_fields = [
        'Product.Title',
        'Quantity',
        'Price.Nice',
    ];

    /**
     * @var string
     */
    private static $table_name = 'FoxyOrderDetail';
}
