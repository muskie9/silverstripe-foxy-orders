<?php

namespace Dynamic\Foxy\Orders\Admin;

use Dynamic\Foxy\Orders\Model\Order;
use SilverStripe\Admin\ModelAdmin;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordViewer;

/**
 * Class OrderAdmin
 * @package Dynamic\Foxy\Orders\Admin
 */
class OrderAdmin extends ModelAdmin
{
    /**
     * @var array
     */
    private static $managed_models = [
        Order::class,
    ];

    /**
     * @var string
     */
    private static $url_segment = 'orders';

    /**
     * @var string
     */
    private static $menu_title = 'Orders';

    /**
     * @var int
     */
    private static $menu_priority = 4;

    /**
     * @param null $id
     * @param null $fields
     *
     * @return Form
     */
    public function getEditForm($id = null, $fields = null)
    {
        $form = parent::getEditForm($id, $fields);

        $gridFieldName = $this->sanitiseClassName($this->modelClass);
        /** @var GridField $gridField */
        $gridField = $form->Fields()->fieldByName($gridFieldName);

        $gridField->setConfig(GridFieldConfig_RecordViewer::create());

        return $form;
    }
}
