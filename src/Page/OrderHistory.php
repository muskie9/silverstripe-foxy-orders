<?php

namespace Dynamic\Foxy\Orders\Page;

use SilverStripe\Security\Security;

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
     * @return bool
     */
    public function getOrderList()
    {
        if ($Member = Security::getCurrentUser()) {
            $list = $Member->Orders()->sort('TransactionDate', 'DESC');
            return $list;
        }

        return false;
    }
}
