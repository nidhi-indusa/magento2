<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Framework\Pricing\Render;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Pricing\Amount\AmountInterface;
use Magento\Framework\Pricing\SaleableInterface;
use Magento\Framework\Pricing\Price\PriceInterface;
use Magento\Framework\View\Element\Template;

/**
 * Default price box renderer
 *
 * @method bool hasListClass()
 * @method string getListClass()
 * @since 2.0.0
 */
class PriceBox extends Template implements PriceBoxRenderInterface, IdentityInterface
{
    /** Default block lifetime */
    const DEFAULT_LIFETIME = 3600;

    /**
     * @var SaleableInterface
     * @since 2.0.0
     */
    protected $saleableItem;

    /**
     * @var PriceInterface
     * @since 2.0.0
     */
    protected $price;

    /**
     * @var RendererPool
     * @since 2.0.0
     */
    protected $rendererPool;

    /**
     * @param Template\Context  $context
     * @param SaleableInterface $saleableItem
     * @param PriceInterface    $price
     * @param RendererPool      $rendererPool
     * @param array             $data
     * @since 2.0.0
     */
    public function __construct(
        Template\Context $context,
        SaleableInterface $saleableItem,
        PriceInterface $price,
        RendererPool $rendererPool,
        array $data = []
    ) {
        $this->saleableItem = $saleableItem;
        $this->price = $price;
        $this->rendererPool = $rendererPool;
        parent::__construct($context, $data);
    }

    /**
     * @return string
     * @since 2.0.0
     */
    protected function _toHtml()
    {
        $cssClasses = $this->hasData('css_classes') ? explode(' ', $this->getData('css_classes')) : [];
        $cssClasses[] = 'price-' . $this->getPrice()->getPriceCode();
        $this->setData('css_classes', implode(' ', $cssClasses));
        return parent::_toHtml();
    }

    /**
     * Get Key for caching block content
     *
     * @return string
     * @since 2.1.0
     */
    public function getCacheKey()
    {
        return parent::getCacheKey() . '-' . $this->getPriceId() . '-' . $this->getPrice()->getPriceCode();
    }

    /**
     * Get block cache life time
     *
     * @return int
     * @since 2.1.0
     */
    protected function getCacheLifetime()
    {
        return parent::hasCacheLifetime() ? parent::getCacheLifetime() : null;
    }
    
    /**
     * @return SaleableInterface
     * @since 2.0.0
     */
    public function getSaleableItem()
    {
        return $this->saleableItem;
    }

    /**
     * @return PriceInterface
     * @since 2.0.0
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Get price id
     *
     * @param null|string $defaultPrefix
     * @param null|string $defaultSuffix
     * @return string
     * @since 2.0.0
     */
    public function getPriceId($defaultPrefix = null, $defaultSuffix = null)
    {
        if ($this->hasData('price_id')) {
            return $this->getData('price_id');
        }
        $priceId = $this->saleableItem->getId();
        $prefix = $this->hasData('price_id_prefix') ? $this->getData('price_id_prefix') : $defaultPrefix;
        $suffix = $this->hasData('price_id_suffix') ? $this->getData('price_id_suffix') : $defaultSuffix;
        $priceId = $prefix . $priceId . $suffix;
        return $priceId;
    }

    /**
     * Retrieve price object of given type and quantity
     *
     * @param string $priceCode
     * @return PriceInterface
     * @since 2.0.0
     */
    public function getPriceType($priceCode)
    {
        return $this->saleableItem->getPriceInfo()->getPrice($priceCode);
    }

    /**
     * @param AmountInterface $amount
     * @param array $arguments
     * @return string
     * @since 2.0.0
     */
    public function renderAmount(AmountInterface $amount, array $arguments = [])
    {
        $arguments = array_replace($this->getData(), $arguments);

        //@TODO AmountInterface does not contain toHtml() method
        return $this->getAmountRender($amount, $arguments)->toHtml();
    }

    /**
     * @param AmountInterface $amount
     * @param array $arguments
     * @return AmountRenderInterface
     * @since 2.0.0
     */
    protected function getAmountRender(AmountInterface $amount, array $arguments = [])
    {
        return $this->rendererPool->createAmountRender(
            $amount,
            $this->getSaleableItem(),
            $this->getPrice(),
            $arguments
        );
    }

    /**
     * @return RendererPool
     * @since 2.0.0
     */
    public function getRendererPool()
    {
        return $this->rendererPool;
    }

    /**
     * Return unique ID(s) for each object in system
     *
     * @return array
     * @since 2.1.0
     */
    public function getIdentities()
    {
        $item = $this->getSaleableItem();
        if ($item instanceof IdentityInterface) {
            return $item->getIdentities();
        } else {
            return [];
        }
    }
}
