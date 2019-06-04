<?php

namespace Dynamic\FoxyStripe\Foxy;

use Dynamic\Foxy\Model\Setting;
use SilverStripe\Core\Injector\Injectable;
use SilverStripe\ORM\ValidationException;

/**
 * Class Transaction
 * @package Dynamic\FoxyStripe\Foxy
 */
class Transaction
{
    use Injectable;

    /**
     * @var
     */
    private $transaction;

    /**
     * Transaction constructor.
     * @param $transactionID
     * @param $data
     * @throws ValidationException
     */
    public function __construct($transactionID, $data)
    {
        $this->setTransaction($transactionID, $data);
    }

    /**
     * @param $transactionID
     * @param $data
     * @return $this
     * @throws ValidationException
     */
    public function setTransaction($transactionID, $data)
    {
        $decryptedData = $this->getDecryptedData($data);

        foreach ($decryptedData->transactions->transaction as $transaction) {
            if ($transactionID == (int)$transaction->id) {
                $this->transaction = $transaction;
                break;
            }
        }

        if (!$this->transaction) {
            $this->transaction = false;
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * @return bool
     */
    public function exists()
    {
        return $this->getTransaction() != false && !empty($this->getTransaction());
    }

    /**
     * @param $data
     * @return \SimpleXMLElement
     * @throws \SilverStripe\ORM\ValidationException
     */
    private function getDecryptedData($data)
    {
        $config = Setting::current_foxy_setting();
        return new \SimpleXMLElement(\rc4crypt::decrypt($config::getStoreKey(), $data));
    }
}