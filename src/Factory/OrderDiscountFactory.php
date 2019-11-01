<?php

namespace Dynamic\Foxy\Orders\Factory;

use Dynamic\Foxy\Orders\Model\Order;
use Dynamic\Foxy\Orders\Model\OrderDiscount;
use SilverStripe\Core\Extensible;
use SilverStripe\ORM\ValidationException;
use SilverStripe\View\ArrayData;

/**
 * Class OrderDiscountFactory
 * @package Dynamic\Foxy\Orders\Factory
 */
class OrderDiscountFactory extends FoxyFactory
{
    use Extensible;

    /**
     * @var array
     */
    private $discount_records = [];

    /**
     * @return array
     * @throws ValidationException
     */
    public function getOrderDiscounts(): array
    {
        if (empty($this->discount_records)) {
            $this->setOrderDiscounts();
        }

        return $this->discount_records;
    }

    /**
     * @return $this
     * @throws ValidationException
     */
    public function setOrderDiscounts(): self
    {
        /** @var ArrayData $transaction */
        $discounts = $this->getTransaction()->getParsedTransactionData()->getField('discounts');
        $transactionID = $this->getTransaction()->getParsedTransactionData()->transaction->getField('id');
        $discountRecords = [];

        foreach ($discounts as $discount) {
            $orderDiscount = OrderDiscount::create();

            foreach ($this->config()->get('order_discount_mapping') as $foxy => $ssFoxy) {
                if ($discount->hasField($foxy)) {
                    $orderDiscount->{$ssFoxy} = $discount->getField($foxy);
                }
            }

            $orderDiscount->OrderID = Order::get()->filter('OrderID', $transactionID)->first();
            $orderDiscount->write();

            $discountRecords[] = $orderDiscount;
        }

        $this->discount_records = $discountRecords;

        return $this;
    }
}
