<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Chooser Container for "Product Link" Cms Widget Plugin
 *
 * @author     Magento Core Team <core@magentocommerce.com>
 */
namespace Magento\Catalog\Block\Adminhtml\Product\Widget\Chooser;

use Magento\Backend\Block\Template;

/**
 * Class \Magento\Catalog\Block\Adminhtml\Product\Widget\Chooser\Container
 *
 * @since 2.0.0
 */
class Container extends Template
{
    /**
     * @var string
     * @since 2.0.0
     */
    protected $_template = 'catalog/product/widget/chooser/container.phtml';
}
