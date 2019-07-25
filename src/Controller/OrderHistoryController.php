<?php

namespace Dynamic\Foxy\Orders\Page;

use SilverStripe\Control\HTTPRequest;
use SilverStripe\ORM\PaginatedList;
use SilverStripe\Security\Security;

class OrderHistoryController extends \PageController
{
    /**
     * @var array
     */
    private static $allowed_actions = array(
        'index',
    );

    /**
     * @return bool|\SilverStripe\Control\HTTPResponse
     */
    public function checkMember()
    {
        if (Security::getCurrentUser()) {
            return true;
        } else {
            return Security::permissionFailure($this, _t(
                'AccountPage.CANNOTCONFIRMLOGGEDIN',
                'Please login to view this page.'
            ));
        }
    }

    /**
     * @return array
     */
    public function index()
    {
        $this->checkMember();
        return array();
    }

    /**
     * @param HTTPRequest|null $request
     * @return PaginatedList
     */
    public function OrderPaginatedList(HTTPRequest $request = null)
    {
        if (!$request instanceof HTTPRequest) {
            $request = $this->getRequest();
        }
        $orders = $this->data()->getOrderList();
        $start = ($request->getVar('start')) ? (int)$request->getVar('start') : 0;
        $records = PaginatedList::create($orders, $request);
        $records->setPageStart($start);
        $records->setPageLength($this->data()->PerPage);

        // allow $records to be updated via extension
        $this->extend('updateOrderPaginatedList', $records);

        return $records;
    }
}
