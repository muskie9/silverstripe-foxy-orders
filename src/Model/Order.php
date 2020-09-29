<?php

namespace Dynamic\Foxy\Orders\Model;

use Dynamic\Foxy\Extension\Purchasable;
use SilverStripe\Forms\DateField;
use SilverStripe\Forms\FieldList;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\FieldType\DBHTMLVarchar;
use SilverStripe\ORM\HasManyList;
use SilverStripe\Security\Member;
use SilverStripe\Security\Permission;
use SilverStripe\Security\PermissionProvider;

/**
 * Class Order
 * @package Dynamic\Foxy\Model
 *
 * @property \SilverStripe\ORM\FieldType\DBInt StoreID
 * @property \SilverStripe\ORM\FieldType\DBInt OrderID
 * @property \SilverStripe\ORM\FieldType\DBVarchar Email
 * @property \SilverStripe\ORM\FieldType\DBDatetime TransactionDate
 * @property \SilverStripe\ORM\FieldType\DBCurrency ProductTotal
 * @property \SilverStripe\ORM\FieldType\DBCurrency TaxTotal
 * @property \SilverStripe\ORM\FieldType\DBCurrency ShippingTotal
 * @property \SilverStripe\ORM\FieldType\DBCurrency OrderTotal
 * @property \SilverStripe\ORM\FieldType\DBVarchar ReceiptURL
 * @property \SilverStripe\ORM\FieldType\DBVarchar OrderStatus
 * @property \SilverStripe\ORM\FieldType\DBText Response
 *
 * @property int MemberID
 * @method Member Member
 *
 * @method HasManyList Details
 */
class Order extends DataObject implements PermissionProvider
{
    /**
     * @var array
     */
    private static $db = [
        'StoreID' => 'Int',
        'OrderID' => 'Int',
        'Email' => 'Varchar(255)',
        'TransactionDate' => 'DBDatetime',
        'ProductTotal' => 'Currency',
        'TaxTotal' => 'Currency',
        'ShippingTotal' => 'Currency',
        'OrderTotal' => 'Currency',
        'ReceiptURL' => 'Varchar(255)',
        'OrderStatus' => 'Varchar(255)',
        'Response' => 'Text',
        'CustomerID' => 'Int',
    ];

    /**
     * @var array
     */
    private static $has_one = [
        'Member' => Member::class,
    ];

    /**
     * @var array
     */
    private static $has_many = [
        'Details' => OrderDetail::class,
    ];

    /**
     * @var string
     */
    private static $singular_name = 'Order';

    /**
     * @var string
     */
    private static $plural_name = 'Orders';

    /**
     * @var string
     */
    private static $description = 'Orders from FoxyCart Datafeed';

    /**
     * @var string
     */
    private static $default_sort = 'TransactionDate DESC, ID DESC';

    /**
     * @var array
     */
    private static $summary_fields = [
        'OrderID',
        'TransactionDate.Nice',
        'Email',
        'ProductTotal.Nice',
        'ShippingTotal.Nice',
        'TaxTotal.Nice',
        'OrderTotal.Nice',
        'ReceiptLink',
    ];

    /**
     * @var array
     */
    private static $searchable_fields = [
        'OrderID',
        'TransactionDate' => [
            'field' => DateField::class,
            'filter' => 'PartialMatchFilter',
        ],
        'Email',
        'OrderTotal',
    ];

    /**
     * @var array
     */
    private static $casting = [
        'ReceiptLink' => 'HTMLVarchar',
    ];

    /**
     * @var array
     */
    private static $indexes = [
        'OrderID' => true, // make unique
    ];

    /**
     * @var string
     */
    private static $table_name = 'FoxyOrder';

    /**
     * @param bool $includerelations
     *
     * @return array|string
     */
    public function fieldLabels($includerelations = true)
    {
        $labels = parent::fieldLabels();
        $labels['StoreID'] = _t(__CLASS__ . '.StoreID', 'Store ID#');
        $labels['OrderID'] = _t(__CLASS__ . '.OrderID', 'Order ID#');
        $labels['TransactionDate'] = _t(__CLASS__ . '.TransactionDate', 'Date');
        $labels['TransactionDate.NiceUS'] = _t(__CLASS__ . '.TransactionDate', 'Date');
        $labels['Email'] = _t(__CLASS__ . '.Email', 'Email');
        $labels['ProductTotal.Nice'] = _t(__CLASS__ . '.ProductTotal', 'Sub Total');
        $labels['TaxTotal.Nice'] = _t(__CLASS__ . '.TaxTotal', 'Tax');
        $labels['ShippingTotal.Nice'] = _t(__CLASS__ . '.ShippingTotal', 'Shipping');
        $labels['OrderTotal'] = _t(__CLASS__ . '.OrderTotal', 'Total');
        $labels['OrderTotal.Nice'] = _t(__CLASS__ . '.OrderTotal', 'Total');
        $labels['ReceiptLink'] = _t(__CLASS__ . '.ReceiptLink', 'Invoice');
        $labels['Details.ProductID'] = _t(__CLASS__ . '.Details.ProductID', 'Product');

        return $labels;
    }

    /**
     * @return FieldList
     */
    public function getCMSFields()
    {
        $this->beforeUpdateCMSFields(function (FieldList $fields) {
            $fields->removeByName(['Response']);
        });

        return parent::getCMSFields();
    }

    /**
     * @return mixed
     */
    public function ReceiptLink()
    {
        return $this->getReceiptLink();
    }

    /**
     * @return mixed
     */
    public function getReceiptLink()
    {
        $obj = DBHTMLVarchar::create();
        $obj->setValue(
            '<a href="' . $this->ReceiptURL . '" target="_blank" class="cms-panel-link action external-link">view</a>'
        );

        return $obj;
    }

    /**
     * @return array
     */
    public function providePermissions()
    {
        return [
            'MANAGE_FOXY_ORDERS' => [
                'name' => _t(
                    __CLASS__ . '.PERMISSION_MANAGE_ORDERS_DESCRIPTION',
                    'Manage orders'
                ),
                'category' => _t(
                    Purchasable::class . '.PERMISSIONS_CATEGORY',
                    'Foxy'
                ),
                'help' => _t(
                    __CLASS__ . '.PERMISSION_MANAGE_ORDERS_HELP',
                    'Manage orders and view recipts'
                ),
                'sort' => 400,
            ],
        ];
    }

    /**
     * @param bool $member
     *
     * @return bool|int
     */
    public function canView($member = null)
    {
        return Permission::checkMember($member, 'MANAGE_FOXY_ORDERS');
    }

    /**
     * @param null $member
     *
     * @return bool
     */
    public function canEdit($member = null)
    {
        return false;
    }

    /**
     * @param null $member
     *
     * @return bool
     */
    public function canDelete($member = null)
    {
        return false;
    }

    /**
     * @param null $member
     * @param array $context
     *
     * @return bool
     */
    public function canCreate($member = null, $context = [])
    {
        return false;
    }
}
