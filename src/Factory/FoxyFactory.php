<?php

namespace Dynamic\Foxy\Orders\Factory;

use Dynamic\Foxy\Parser\Foxy\Transaction;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Injector\Injectable;

/**
 * Class FoxyFactory
 * @package Dynamic\Foxy\Orders\Factory
 */
class FoxyFactory
{
    use Configurable;
    use Injectable;

    /**
     * @var Transaction
     */
    private $transaction;

    /**
     * OrderDetailFactory constructor.
     * @param Transaction|null $transaction
     */
    public function __construct(Transaction $transaction = null)
    {
        if ($transaction instanceof Transaction && $transaction !== null) {
            $this->setTransaction($transaction);
        }
    }

    /**
     * @param Transaction $transaction
     * @return $this
     */
    public function setTransaction(Transaction $transaction)
    {
        $this->transaction = $transaction;

        return $this;
    }

    /**
     * @return Transaction
     */
    protected function getTransaction()
    {
        return $this->transaction;
    }
}
