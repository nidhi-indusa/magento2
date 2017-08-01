<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * CatalogSearch Index Engine Interface
 */
namespace Magento\CatalogSearch\Model\ResourceModel;

/**
 * @api
 * @since 2.0.0
 */
interface EngineInterface
{
    const FIELD_PREFIX = 'attr_';

    /**
     * Scope identifier
     */
    const SCOPE_IDENTIFIER = 'scope';

    /**
     * Configuration path by which current indexer handler stored
     */
    const CONFIG_ENGINE_PATH = 'catalog/search/engine';

    /**
     * Retrieve allowed visibility values for current engine
     *
     * @return array
     * @since 2.0.0
     */
    public function getAllowedVisibility();

    /**
     * Define if current search engine supports advanced index
     *
     * @return bool
     * @since 2.0.0
     */
    public function allowAdvancedIndex();

    /**
     * Prepare attribute value to store in index
     *
     * @param \Magento\Eav\Model\Entity\Attribute $attribute
     * @param mixed $value
     * @return mixed
     * @since 2.0.0
     */
    public function processAttributeValue($attribute, $value);

    /**
     * Prepare index array as a string glued by separator
     *
     * @param array $index
     * @param string $separator
     * @return string
     * @since 2.0.0
     */
    public function prepareEntityIndex($index, $separator = ' ');
}
