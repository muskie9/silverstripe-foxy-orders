<?php

namespace Dynamic\Foxy\Orders\Tests\TestOnly\Page;

use Dynamic\Foxy\Extension\Purchasable;
use Dynamic\Foxy\Model\Variation;
use SilverStripe\Dev\TestOnly;

/**
 * Class TestProduct
 * @package Dynamic\Foxy\Orders\Tests\TestOnly\Page
 */
class TestProduct extends \Page implements TestOnly
{
    /**
     * @var array
     */
    private static $extensions = [
        Purchasable::class,
    ];

    /**
     * @var string[]
     */
    private static $has_many = [
        'Variations' => Variation::class,
    ];
}
