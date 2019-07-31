<?php

namespace Dynamic\Foxy\Orders\Page;

use Dynamic\Foxy\Orders\Model\Order;
use SilverStripe\Security\Security;

/**
 * Class OrderHistory
 * @package Dynamic\Foxy\Orders\Page
 */
class OrderHistory extends \Page
{
    /**
     * @var string
     */
    private static $singular_name = 'Order History';

    /**
     * @var string
     */
    private static $plural_name = 'Order Histories';

    /**
     * @var string
     */
    private static $description = 'Show a customers past orders. Requires authentication';

    /**
     * @var array
     */
    private static $db = [
        'PerPage' => 'Int',
    ];

    /**
     * @var array
     */
    private static $defaults = [
        'PerPage' => 10,
    ];

    /**
     * @var string
     */
    private static $table_name = 'FoxyOrderHistory';

    /**
     * return all current Member's Orders.
     *
     * @return bool|\SilverStripe\ORM\DataList
     */
    public function getOrderList()
    {
        if ($member = Security::getCurrentUser()) {
            $list = Order::get()->filter('Email', $member->Email)->sort('TransactionDate', 'DESC');

            return $list;
        }

        return false;
    }
}
