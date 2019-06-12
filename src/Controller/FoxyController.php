<?php

namespace Dynamic\Foxy\Controller;

use Dynamic\Foxy\Model\FoxyHelper;
use Dynamic\Foxy\Model\Order;
use SilverStripe\Control\Controller;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\ORM\ValidationException;
use SilverStripe\Security\Member;
use SilverStripe\Security\Security;

class FoxyController extends Controller
{
    /**
     *
     */
    const URLSEGMENT = 'foxy';

    /**
     * @var array
     */
    private static $allowed_actions = [
        'index',
    ];

    /**
     * @return string
     */
    public function getURLSegment()
    {
        return self::URLSEGMENT;
    }

    /**
     * @return string
     * @throws ValidationException
     */
    public function index()
    {
        $request = $this->getRequest();
        if ($request->postVar('FoxyData') || $request->postVar('FoxySubscriptionData')) {
            $this->processFoxyRequest($request);
            return 'foxy';
        }
        return 'No FoxyData or FoxySubscriptionData received.';
    }

    /**
     * Process a request after a transaction is completed via Foxy
     *
     * @param HTTPRequest $request
     * @throws ValidationException
     */
    protected function processFoxyRequest(HTTPRequest $request)
    {
        $encryptedData = $request->postVar('FoxyData')
            ? urldecode($request->postVar('FoxyData'))
            : urldecode($request->postVar('FoxySubscriptionData'));
        $decryptedData = $this->decryptFeedData($encryptedData);
        $this->parseFeedData($encryptedData, $decryptedData);

        $this->extend('addIntegrations', $encryptedData);
    }

    /**
     * Decrypt the XML data feed from Foxy
     *
     * @param $data
     * @return string
     * @throws \SilverStripe\ORM\ValidationException
     */
    private function decryptFeedData($data)
    {
        $helper = FoxyHelper::create();
        return \rc4crypt::decrypt($helper->config()->get('secret'), $data);
    }

    /**
     * Parse the XML data feed from Foxy to a SimpleXMLElement object
     *
     * @param $encryptedData
     * @param $decryptedData
     * @throws ValidationException
     */
    private function parseFeedData($encryptedData, $decryptedData)
    {
        $orders = new \SimpleXMLElement($decryptedData);
        // loop over each transaction to find FoxyCart Order ID
        foreach ($orders->transactions->transaction as $transaction) {
            $this->processTransaction($transaction, $encryptedData);
        }
    }

    /**
     * @param $transaction
     * @return bool
     * @throws ValidationException
     */
    private function processTransaction($transaction, $encryptedData)
    {
        if (!isset($transaction->id)) {
            return false;
        }
        if (!$order = Order::get()->filter('OrderID', (int)$transaction->id)->first()) {
            $order = Order::create();
            $order->OrderID = (int)$transaction->id;
            $order->Response = urlencode($encryptedData);
        }
        $order->write();
    }
}
