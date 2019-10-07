<?php

namespace Dynamic\Foxy\Orders\Factory;

use Dynamic\Foxy\Orders\Model\Order;
use Dynamic\Foxy\Orders\Model\OrderDiscount;
use SilverStripe\Core\Extensible;
use SilverStripe\View\ArrayData;

/**
 * Class OrderDiscountFactory
 * @package Dynamic\Foxy\Orders\Factory
 */
class OrderDiscountFactory extends FoxyFactory
{
    use Extensible;

    /**
     * @var OrderDiscount
     */
    private $order_discount;

    /**
     * @return OrderDiscount
     * @throws \SilverStripe\ORM\ValidationException
     */
    public function getOrderDiscount(): OrderDiscount
    {
        if (!$this->order_discount instanceof OrderDiscount) {
            $this->setOrderDiscount();
        }

        return $this->order_discount;
    }

    /**
     * @return $this
     * @throws \SilverStripe\ORM\ValidationException
     */
    public function setOrderDiscount(): self
    {
        /** @var ArrayData $transaction */
        $discounts = $this->getTransaction()->getParsedTransactionData()->getField('discounts');
        $transactionID = $this->getTransaction()->getParsedTransactionData()->transaction->getField('id');

        foreach ($discounts as $discount) {
            $orderDiscount = OrderDiscount::create();

            foreach ($this->config()->get('order_discount_mapping') as $foxy => $ssFoxy) {
                if ($discount->hasField($foxy)) {
                    $orderDiscount->{$ssFoxy} = $discount->getField($foxy);
                }
            }

            $orderDiscount->OrderID = Order::get()->filter('OrderID', $transactionID)->first();
            $orderDiscount->write();
        }
    }
}
