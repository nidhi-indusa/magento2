<?php
/**
 * Newsletter subscriber grid collection
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Newsletter\Model\ResourceModel\Subscriber\Grid;

/**
 * Class \Magento\Newsletter\Model\ResourceModel\Subscriber\Grid\Collection
 *
 * @since 2.0.0
 */
class Collection extends \Magento\Newsletter\Model\ResourceModel\Subscriber\Collection
{
    /**
     * Sets flag for customer info loading on load
     *
     * @return $this
     * @since 2.0.0
     */
    protected function _initSelect()
    {
        parent::_initSelect();
        $this->showCustomerInfo(true)->addSubscriberTypeField()->showStoreInfo();
        return $this;
    }
}
