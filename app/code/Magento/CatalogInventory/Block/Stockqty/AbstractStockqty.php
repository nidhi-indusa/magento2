<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogInventory\Block\Stockqty;

use Magento\Catalog\Model\Product;

/**
 * Product stock qty abstract block
 * @since 2.0.0
 */
abstract class AbstractStockqty extends \Magento\Framework\View\Element\Template
{
    /**
     * Threshold qty config path
     * @deprecated
     * @see \Magento\CatalogInventory\Model\Configuration::XML_PATH_STOCK_THRESHOLD_QTY
     */
    const XML_PATH_STOCK_THRESHOLD_QTY = 'cataloginventory/options/stock_threshold_qty';

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     * @since 2.0.0
     */
    protected $_coreRegistry;

    /**
     * @var \Magento\CatalogInventory\Api\StockStateInterface
     * @since 2.0.0
     */
    protected $stockState;

    /**
     * @var \Magento\CatalogInventory\Api\StockRegistryInterface
     * @since 2.0.0
     */
    protected $stockRegistry;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\CatalogInventory\Api\StockStateInterface $stockState
     * @param \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry
     * @param array $data
     * @since 2.0.0
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\CatalogInventory\Api\StockStateInterface $stockState,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        $this->stockState = $stockState;
        $this->stockRegistry = $stockRegistry;

        parent::__construct($context, $data);
    }

    /**
     * Retrieve current product object
     *
     * @return \Magento\Catalog\Model\Product
     * @since 2.0.0
     */
    public function getProduct()
    {
        return $this->_coreRegistry->registry('current_product');
    }

    /**
     * Retrieve current product stock qty
     *
     * @return float
     * @since 2.0.0
     */
    public function getStockQty()
    {
        if (!$this->hasData('product_stock_qty')) {
            $qty = 0;
            $productId = $this->getProduct()->getId();
            if ($productId) {
                $qty = $this->getProductStockQty($this->getProduct());
            }
            $this->setData('product_stock_qty', $qty);
        }
        return $this->getData('product_stock_qty');
    }

    /**
     * Retrieve product stock qty
     *
     * @param Product $product
     * @return float
     * @since 2.0.0
     */
    public function getProductStockQty($product)
    {
        return $this->stockRegistry->getStockStatus($product->getId(), $product->getStore()->getWebsiteId())->getQty();
    }

    /**
     * Retrieve threshold of qty to display stock qty message
     *
     * @return string
     * @since 2.0.0
     */
    public function getThresholdQty()
    {
        if (!$this->hasData('threshold_qty')) {
            $qty = (float)$this->_scopeConfig->getValue(
                self::XML_PATH_STOCK_THRESHOLD_QTY,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );
            $this->setData('threshold_qty', $qty);
        }
        return $this->getData('threshold_qty');
    }

    /**
     * Retrieve id of message placeholder in template
     *
     * @return string
     * @since 2.0.0
     */
    public function getPlaceholderId()
    {
        return 'stock-qty-' . $this->getProduct()->getId();
    }

    /**
     * Retrieve visibility of stock qty message
     *
     * @return bool
     * @since 2.0.0
     */
    public function isMsgVisible()
    {
        return $this->getStockQty() > 0 && $this->getStockQtyLeft() <= $this->getThresholdQty();
    }

    /**
     * Retrieve current product qty left in stock
     *
     * @return float
     * @since 2.0.0
     */
    public function getStockQtyLeft()
    {
        $stockItem = $this->stockRegistry->getStockItem($this->getProduct()->getId());
        $minStockQty = $stockItem->getMinQty();
        return $this->getStockQty() - $minStockQty;
    }
}
