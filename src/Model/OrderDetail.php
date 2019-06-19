<?php

namespace Dynamic\Foxy\Model;

use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\ORM\DataObject;

class OrderDetail extends DataObject
{
    /**
     * @var array
     */
    private static $db = array(
        'Quantity' => 'Int',
        'Price' => 'Currency',
        'ProductName' => 'Varchar(255)',
        'ProductCode' => 'Varchar(100)',
        'ProductImage' => 'Text',
        'ProductCategory' => 'Varchar(100)',
    );

    /**
     * @var array
     */
    private static $has_one = array(
        'Product' => SiteTree::class,
        'Order' => Order::class,
    );

    /**
     * @var array
     */
    private static $has_many = array(
        'OrderOptions' => OrderOption::class,
    );

    /**
     * @var array
     */
    private static $summary_fields = array(
        'Product.Title',
        'Quantity',
        'Price.Nice',
    );

    /**
     * @var string
     */
    private static $table_name = 'FoxyOrderDetail';

    /**
     * @return FieldList
     */
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        return $fields;
    }
}
