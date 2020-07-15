<?php

namespace Dynamic\Foxy\Orders\Model;

use Dynamic\Foxy\Model\Variation;
use SilverStripe\ORM\DataObject;

class OrderVariation extends DataObject
{
    /**
     * @var array
     */
    private static $db = array(
        'Name' => 'Varchar(200)',
        'Value' => 'Varchar(200)',
    );

    /**
     * @var array
     */
    private static $has_one = array(
        'OrderDetail' => OrderDetail::class,
        'Variation' => Variation::class,
    );

    /**
     * @var array
     */
    private static $summary_fields = array(
        'Name',
        'Value',
    );

    /**
     * @var string
     */
    private static $table_name = 'FoxyOrderVariation';
}
