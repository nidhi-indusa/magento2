<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\CatalogSearch\Model\Search;

use Magento\Search\Model\QueryFactory;

/**
 * Search model for backend search
 *
 * @deprecated 2.2.0
 * @since 2.0.0
 */
class Catalog extends \Magento\Framework\DataObject
{
    /**
     * Catalog search data
     *
     * @var \Magento\Search\Model\QueryFactory
     * @since 2.0.0
     */
    protected $queryFactory = null;

    /**
     * Magento string lib
     *
     * @var \Magento\Framework\Stdlib\StringUtils
     * @since 2.0.0
     */
    protected $string;

    /**
     * Adminhtml data
     *
     * @var \Magento\Backend\Helper\Data
     * @since 2.0.0
     */
    protected $_adminhtmlData = null;

    /**
     * @param \Magento\Backend\Helper\Data $adminhtmlData
     * @param \Magento\Framework\Stdlib\StringUtils $string
     * @param QueryFactory $queryFactory
     * @since 2.0.0
     */
    public function __construct(
        \Magento\Backend\Helper\Data $adminhtmlData,
        \Magento\Framework\Stdlib\StringUtils $string,
        QueryFactory $queryFactory
    ) {
        $this->_adminhtmlData = $adminhtmlData;
        $this->string = $string;
        $this->queryFactory = $queryFactory;
    }

    /**
     * Load search results
     *
     * @return $this
     * @since 2.0.0
     */
    public function load()
    {
        $result = [];
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($result);
            return $this;
        }

        $collection = $this->queryFactory->get()
            ->getSearchCollection()
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('description')
            ->addBackendSearchFilter($this->getQuery())
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();

        foreach ($collection as $product) {
            $description = strip_tags($product->getDescription());
            $result[] = [
                'id' => 'product/1/' . $product->getId(),
                'type' => __('Product'),
                'name' => $product->getName(),
                'description' => $this->string->substr($description, 0, 30),
                'url' => $this->_adminhtmlData->getUrl('catalog/product/edit', ['id' => $product->getId()]),
            ];
        }

        $this->setResults($result);

        return $this;
    }
}
