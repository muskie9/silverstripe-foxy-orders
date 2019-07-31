<?php

namespace Dynamic\Foxy\Orders\Factory;

use Dynamic\Foxy\Orders\Model\OrderDetail;
use SilverStripe\ORM\ArrayList;
use SilverStripe\View\ArrayData;

/**
 * Class OrderDetailFactory
 * @package Dynamic\Foxy\Orders\Factory
 */
class OrderDetailFactory extends FoxyFactory
{
    /**
     * @var ArrayList
     */
    private $order_details;

    /**
     * @return $this
     * @throws \SilverStripe\ORM\ValidationException
     */
    protected function setOrderDetails()
    {
        $details = ArrayList::create();

        /** @var ArrayList $products */
        $products = $this->getTransaction()->getParsedTransactionData()->products;

        /** @var ArrayData $detail */
        foreach ($products as $detail) {
            $orderDetail = OrderDetail::create();

            foreach ($this->config()->get('order_detail_mapping') as $foxy => $ssFoxy) {
                if ($detail->hasField($foxy)) {
                    $orderDetail->{$ssFoxy} = $detail->getField($foxy);
                }
            }

            $orderDetail->write();

            $orderDetail->OrderOptions()->addMany(OrderOptionFactory::create($detail)->getOrderOptions());

            $details->push($orderDetail);
        }

        $this->order_details = $details;

        return $this;
    }

    /**
     * @return ArrayList
     * @throws \SilverStripe\ORM\ValidationException
     */
    public function getOrderDetails()
    {
        if (!$this->order_details) {
            $this->setOrderDetails();
        }

        return $this->order_details;
    }
}
