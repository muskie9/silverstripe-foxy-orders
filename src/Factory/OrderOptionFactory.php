<?php

namespace Dynamic\Foxy\Orders\Factory;

use Dynamic\Foxy\Orders\Model\OrderOption;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Injector\Injectable;
use SilverStripe\ORM\ArrayList;
use SilverStripe\View\ArrayData;

/**
 * Class OrderOptionFactory
 * @package Dynamic\Foxy\Orders\Factory
 */
class OrderOptionFactory
{
    use Configurable;
    use Injectable;

    /**
     * @var
     */
    private $order_options;

    /**
     * @var ArrayData
     */
    private $foxy_product;

    public function __construct(ArrayData $foxyProduct = null)
    {
        if ($foxyProduct instanceof ArrayData && $foxyProduct !== null) {
            $this->setFoxyProduct($foxyProduct);
        }
    }

    /**
     * @param $foxyProduct
     * @return $this
     */
    public function setFoxyProduct($foxyProduct)
    {
        $this->foxy_product = $foxyProduct;

        return $this;
    }

    /**
     * @return ArrayData
     */
    protected function getFoxyProduct()
    {
        return $this->foxy_product;
    }

    /**
     * @return $this
     * @throws \SilverStripe\ORM\ValidationException
     */
    protected function setOrderOptions()
    {
        $options = ArrayList::create();

        /** @var ArrayData $optionItem */
        foreach ($this->getFoxyProduct()->transaction_detail_options as $optionItem) {
            $option = OrderOption::create();

            foreach ($this->config()->get('option_mapping') as $foxyField => $ssField) {
                if ($optionItem->hasField($foxyField)) {
                    $option->{$ssField} = $optionItem->getField($foxyField);
                }
            }

            $option->write();
            $options->push($option);
        }

        $this->order_options = $options;

        return $this;
    }

    /**
     * @return ArrayList
     * @throws \SilverStripe\ORM\ValidationException
     */
    public function getOrderOptions()
    {
        if (!$this->order_options instanceof ArrayList) {
            $this->setOrderOptions();
        }

        return $this->order_options;
    }
}
