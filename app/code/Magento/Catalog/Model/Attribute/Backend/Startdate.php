<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Catalog\Model\Attribute\Backend;

/**
 *
 * Speical Start Date attribute backend
 *
 * @api
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 * @since 2.0.0
 */
class Startdate extends \Magento\Eav\Model\Entity\Attribute\Backend\Datetime
{
    /**
     * Date model
     *
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     * @since 2.0.0
     */
    protected $_date;

    /**
     * Constructor
     *
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     * @since 2.0.0
     */
    public function __construct(
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        \Magento\Framework\Stdlib\DateTime\DateTime $date
    ) {
        $this->_date = $date;
        parent::__construct($localeDate);
    }

    /**
     * Get attribute value for save.
     *
     * @param \Magento\Framework\DataObject $object
     * @return string|bool
     * @since 2.0.0
     */
    protected function _getValueForSave($object)
    {
        $attributeName = $this->getAttribute()->getName();
        $startDate = $object->getData($attributeName);

        return $startDate;
    }

    /**
     * Before save hook.
     * Prepare attribute value for save
     *
     * @param \Magento\Framework\DataObject $object
     * @return $this
     * @since 2.0.0
     */
    public function beforeSave($object)
    {
        $startDate = $this->_getValueForSave($object);
        if ($startDate === false) {
            return $this;
        }

        $object->setData($this->getAttribute()->getName(), $startDate);
        parent::beforeSave($object);
        return $this;
    }

    /**
     * Product from date attribute validate function.
     * In case invalid data throws exception.
     *
     * @param \Magento\Framework\DataObject $object
     * @throws \Magento\Eav\Model\Entity\Attribute\Exception
     * @return bool
     * @since 2.0.0
     */
    public function validate($object)
    {
        $attr = $this->getAttribute();
        $maxDate = $attr->getMaxValue();
        $startDate = $this->_getValueForSave($object);
        if ($startDate === false) {
            return true;
        }

        if ($maxDate) {
            $date = $this->_date;
            $value = $date->timestamp($startDate);
            $maxValue = $date->timestamp($maxDate);

            if ($value > $maxValue) {
                $message = __('Make sure the To Date is later than or the same as the From Date.');
                $eavExc = new \Magento\Eav\Model\Entity\Attribute\Exception($message);
                $eavExc->setAttributeCode($attr->getName());
                throw $eavExc;
            }
        }
        return true;
    }
}
