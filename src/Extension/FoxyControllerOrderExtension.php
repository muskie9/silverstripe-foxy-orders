<?php

namespace Dynamic\Foxy\Orders\Extension;

use Dynamic\Foxy\Orders\Factory\OrderFactory;
use Dynamic\Foxy\Parser\Foxy\Transaction;
use SilverStripe\Core\Extension;

/**
 * Class FoxyControllerOrderExtension
 * @package Dynamic\Foxy\Orders\Extension
 */
class FoxyControllerOrderExtension extends Extension
{
    /**
     * @param Transaction $transaction
     * @throws \SilverStripe\ORM\ValidationException
     */
    public function doAdditionalParse(Transaction $transaction)
    {
        OrderFactory::create($transaction)->getOrder();
    }
}
