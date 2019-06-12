<?php

namespace Dynamic\Foxy\Model;

use Dynamic\FoxyStripe\Foxy\Transaction;
use SilverStripe\Forms\DateField;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\FieldType\DBHTMLVarchar;
use SilverStripe\ORM\ValidationException;
use SilverStripe\Security\Member;

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
 */
class Order extends DataObject
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
    ];

    /**
     * @var array
     */
    private static $has_one = [
        'Member' => Member::class,
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
        'OrderTotal'
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
     * @var
     */
    private $transaction;

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
     * @throws \SilverStripe\ORM\ValidationException
     */
    protected function onBeforeWrite()
    {
        parent::onBeforeWrite();
        $this->parseOrder();
    }

    /**
     * @return mixed
     */
    protected function getTransaction()
    {
        if (!$this->transaction) {
            $this->setTransaction();
        }
        return $this->transaction;
    }

    /**
     * @return $this
     */
    protected function setTransaction()
    {
        if ($this->Response) {
            $this->transaction = Transaction::create($this->OrderID, urldecode($this->Response));
        } else {
            $this->transaction = false;
        }
        return $this;
    }

    /**
     * @return bool
     *
     * @throws ValidationException
     */
    public function parseOrder()
    {
        if ($this->getTransaction() && $this->getTransaction()->exists()) {
            $this->parseOrderInfo();
        }

        $this->extend('updateParseOrder', $this);
    }

    /**
     * @return bool|string
     */
    private function getDecryptedResponse()
    {
        $helper = FoxyHelper::create();
        if ($secret = $helper->config()->get('secret') && $this->Response) {
            return \rc4crypt::decrypt($secret, urldecode($this->Response));
        }
        return false;
    }

    /**
     * @param $response
     */
    public function parseOrderInfo()
    {
        $transaction = $this->getTransaction()->getTransaction();

        // Record transaction data from FoxyCart Datafeed:
        $this->StoreID = (int)$transaction->store_id;
        $this->Email = (string)$transaction->customer_email;
        $this->TransactionDate = (string)$transaction->transaction_date;
        $this->ProductTotal = (float)$transaction->product_total;
        $this->TaxTotal = (float)$transaction->tax_total;
        $this->ShippingTotal = (float)$transaction->shipping_total;
        $this->OrderTotal = (float)$transaction->order_total;
        $this->ReceiptURL = (string)$transaction->receipt_url;
        $this->OrderStatus = (string)$transaction->status;

        $this->extend('handleOrderInfo', $order, $response);
    }
}
