<?php

namespace Dynamic\Foxy\Orders\Factory;

use Dynamic\Foxy\Model\FoxyHelper;
use Dynamic\Foxy\Model\Variation;
use Dynamic\Foxy\Orders\Model\OrderVariation;
use GraphQL\Error\Debug;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Injector\Injectable;
use SilverStripe\ORM\ArrayList;
use SilverStripe\View\ArrayData;

/**
 * Class OrderVariationFactory
 * @package Dynamic\Foxy\Orders\Factory
 */
class OrderVariationFactory
{
    use Configurable;
    use Injectable;

    /**
     * @var
     */
    private $order_variations;

    /**
     * @var ArrayData
     */
    private $foxy_product;

    /**
     * @var
     */
    private $product;

    /**
     * OrderVariationFactory constructor.
     * @param ArrayData|null $foxyProduct
     */
    public function __construct(ArrayData $foxyProduct = null, $productID = 0)
    {
        if ($foxyProduct instanceof ArrayData && $foxyProduct !== null) {
            $this->setFoxyProduct($foxyProduct);
            $this->setProduct($productID);
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
     * @param $productID
     * @return $this
     */
    public function setProduct($productID)
    {
        $this->product = FoxyHelper::singleton()->getProducts()->filter('ID', $productID)->first();

        return $this;
    }

    /**
     * @return mixed
     */
    protected function getProduct()
    {
        return $this->product;
    }

    /**
     * @return $this
     * @throws \SilverStripe\ORM\ValidationException
     */
    public function setOrderVariations()
    {
        $variations = ArrayList::create();

        foreach ($this->getFoxyProduct()->transaction_detail_options as $variationItem) {
            $variation = OrderVariation::create();

            foreach ($this->config()->get('variation_mapping') as $foxyField => $ssField) {
                if ($variationItem->hasField($foxyField)) {
                    $variation->{$ssField} = $variationItem->getField($foxyField);
                }
            }

            $productVariation = Variation::get()->filter([
                'Title' => $variation->Value,
                'ProductID' => $this->getProduct()->ID,
            ])->first();

            if ($productVariation) {
                $variation->VariationID = $productVariation->ID;
            }

            $variation->write();
            $variations->push($variation);
        }

        $this->order_variations = $variations;

        return $this;
    }

    /**
     * @return ArrayList
     */
    public function getOrderVariations()
    {
        if (!$this->order_variations instanceof ArrayList) {
            $this->setOrderVariations();
        }

        return $this->order_variations;
    }
}
