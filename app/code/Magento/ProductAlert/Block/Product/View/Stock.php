<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\ProductAlert\Block\Product\View;

/**
 * Recurring payment view stock
 *
 * @api
 * @since 2.0.0
 */
class Stock extends \Magento\ProductAlert\Block\Product\View
{
    /**
     * Prepare stock info
     *
     * @param string $template
     * @return $this
     * @since 2.0.0
     */
    public function setTemplate($template)
    {
        if (!$this->_helper->isStockAlertAllowed() || !$this->getProduct() || $this->getProduct()->isAvailable()) {
            $template = '';
        } else {
            $this->setSignupUrl($this->_helper->getSaveUrl('stock'));
        }
        return parent::setTemplate($template);
    }
}
