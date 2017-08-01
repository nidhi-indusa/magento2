<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Checkout\CustomerData;

use Magento\Customer\CustomerData\SectionSourceInterface;

/**
 * Cart source
 * @since 2.0.0
 */
class Cart extends \Magento\Framework\DataObject implements SectionSourceInterface
{
    /**
     * @var \Magento\Customer\Model\Session
     * @since 2.0.0
     */
    protected $checkoutSession;

    /**
     * @var \Magento\Checkout\Model\Cart
     * @since 2.0.0
     */
    protected $checkoutCart;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Url
     * @since 2.0.0
     */
    protected $catalogUrl;

    /**
     * @var \Magento\Quote\Model\Quote|null
     * @since 2.0.0
     */
    protected $quote = null;

    /**
     * @var \Magento\Checkout\Helper\Data
     * @since 2.0.0
     */
    protected $checkoutHelper;

    /**
     * @var ItemPoolInterface
     * @since 2.0.0
     */
    protected $itemPoolInterface;

    /**
     * @var int|float
     * @since 2.0.0
     */
    protected $summeryCount;

    /**
     * @var \Magento\Framework\View\LayoutInterface
     * @since 2.0.0
     */
    protected $layout;

    /**
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Catalog\Model\ResourceModel\Url $catalogUrl
     * @param \Magento\Checkout\Model\Cart $checkoutCart
     * @param \Magento\Checkout\Helper\Data $checkoutHelper
     * @param ItemPoolInterface $itemPoolInterface
     * @param \Magento\Framework\View\LayoutInterface $layout
     * @param array $data
     * @codeCoverageIgnore
     * @since 2.0.0
     */
    public function __construct(
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Catalog\Model\ResourceModel\Url $catalogUrl,
        \Magento\Checkout\Model\Cart $checkoutCart,
        \Magento\Checkout\Helper\Data $checkoutHelper,
        ItemPoolInterface $itemPoolInterface,
        \Magento\Framework\View\LayoutInterface $layout,
        array $data = []
    ) {
        parent::__construct($data);
        $this->checkoutSession = $checkoutSession;
        $this->catalogUrl = $catalogUrl;
        $this->checkoutCart = $checkoutCart;
        $this->checkoutHelper = $checkoutHelper;
        $this->itemPoolInterface = $itemPoolInterface;
        $this->layout = $layout;
    }

    /**
     * {@inheritdoc}
     * @since 2.0.0
     */
    public function getSectionData()
    {
        $totals = $this->getQuote()->getTotals();
        return [
            'summary_count' => $this->getSummaryCount(),
            'subtotal' => isset($totals['subtotal'])
                ? $this->checkoutHelper->formatPrice($totals['subtotal']->getValue())
                : 0,
            'possible_onepage_checkout' => $this->isPossibleOnepageCheckout(),
            'items' => $this->getRecentItems(),
            'extra_actions' => $this->layout->createBlock(\Magento\Catalog\Block\ShortcutButtons::class)->toHtml(),
            'isGuestCheckoutAllowed' => $this->isGuestCheckoutAllowed(),
            'website_id' => $this->getQuote()->getStore()->getWebsiteId()
        ];
    }

    /**
     * Get active quote
     *
     * @return \Magento\Quote\Model\Quote
     * @since 2.0.0
     */
    protected function getQuote()
    {
        if (null === $this->quote) {
            $this->quote = $this->checkoutSession->getQuote();
        }
        return $this->quote;
    }

    /**
     * Get shopping cart items qty based on configuration (summary qty or items qty)
     *
     * @return int|float
     * @since 2.0.0
     */
    protected function getSummaryCount()
    {
        if (!$this->summeryCount) {
            $this->summeryCount = $this->checkoutCart->getSummaryQty() ?: 0;
        }
        return $this->summeryCount;
    }

    /**
     * Check if one page checkout is available
     *
     * @return bool
     * @since 2.0.0
     */
    protected function isPossibleOnepageCheckout()
    {
        return $this->checkoutHelper->canOnepageCheckout() && !$this->getQuote()->getHasError();
    }

    /**
     * Get array of last added items
     *
     * @return \Magento\Quote\Model\Quote\Item[]
     * @since 2.0.0
     */
    protected function getRecentItems()
    {
        $items = [];
        if (!$this->getSummaryCount()) {
            return $items;
        }

        foreach (array_reverse($this->getAllQuoteItems()) as $item) {
            /* @var $item \Magento\Quote\Model\Quote\Item */
            if (!$item->getProduct()->isVisibleInSiteVisibility()) {
                $product =  $item->getOptionByCode('product_type') !== null
                    ? $item->getOptionByCode('product_type')->getProduct()
                    : $item->getProduct();

                $products = $this->catalogUrl->getRewriteByProductStore([$product->getId() => $item->getStoreId()]);
                if (!isset($products[$product->getId()])) {
                    continue;
                }
                $urlDataObject = new \Magento\Framework\DataObject($products[$product->getId()]);
                $item->getProduct()->setUrlDataObject($urlDataObject);
            }
            $items[] = $this->itemPoolInterface->getItemData($item);
        }
        return $items;
    }

    /**
     * Return customer quote items
     *
     * @return \Magento\Quote\Model\Quote\Item[]
     * @since 2.0.0
     */
    protected function getAllQuoteItems()
    {
        if ($this->getCustomQuote()) {
            return $this->getCustomQuote()->getAllVisibleItems();
        }
        return $this->getQuote()->getAllVisibleItems();
    }

    /**
     * Check if guest checkout is allowed
     *
     * @return bool
     * @since 2.0.0
     */
    public function isGuestCheckoutAllowed()
    {
        return $this->checkoutHelper->isAllowedGuestCheckout($this->checkoutSession->getQuote());
    }
}
