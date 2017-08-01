<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Review\Model\Rating;

/**
 * Rating option model
 *
 * @api
 *
 * @method \Magento\Review\Model\ResourceModel\Rating\Option _getResource()
 * @method \Magento\Review\Model\ResourceModel\Rating\Option getResource()
 * @method int getRatingId()
 * @method \Magento\Review\Model\Rating\Option setRatingId(int $value)
 * @method string getCode()
 * @method \Magento\Review\Model\Rating\Option setCode(string $value)
 * @method int getValue()
 * @method \Magento\Review\Model\Rating\Option setValue(int $value)
 * @method int getPosition()
 * @method \Magento\Review\Model\Rating\Option setPosition(int $value)
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 * @codeCoverageIgnore
 * @since 2.0.0
 */
class Option extends \Magento\Framework\Model\AbstractModel
{
    /**
     * @return void
     * @since 2.0.0
     */
    protected function _construct()
    {
        $this->_init(\Magento\Review\Model\ResourceModel\Rating\Option::class);
    }

    /**
     * @return $this
     * @since 2.0.0
     */
    public function addVote()
    {
        $this->getResource()->addVote($this);
        return $this;
    }

    /**
     * @param mixed $id
     * @return $this
     * @since 2.0.0
     */
    public function setId($id)
    {
        $this->setOptionId($id);
        return $this;
    }
}
