<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Sales\Model\Order\Email\Container;

/**
 * Class \Magento\Sales\Model\Order\Email\Container\CreditmemoIdentity
 *
 * @since 2.0.0
 */
class CreditmemoIdentity extends Container implements IdentityInterface
{
    const XML_PATH_EMAIL_COPY_METHOD = 'sales_email/creditmemo/copy_method';
    const XML_PATH_EMAIL_COPY_TO = 'sales_email/creditmemo/copy_to';
    const XML_PATH_EMAIL_IDENTITY = 'sales_email/creditmemo/identity';
    const XML_PATH_EMAIL_GUEST_TEMPLATE = 'sales_email/creditmemo/guest_template';
    const XML_PATH_EMAIL_TEMPLATE = 'sales_email/creditmemo/template';
    const XML_PATH_EMAIL_ENABLED = 'sales_email/creditmemo/enabled';

    /**
     * @return bool
     * @since 2.0.0
     */
    public function isEnabled()
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_EMAIL_ENABLED,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->getStore()->getStoreId()
        );
    }

    /**
     * @return array|bool
     * @since 2.0.0
     */
    public function getEmailCopyTo()
    {
        $data = $this->getConfigValue(self::XML_PATH_EMAIL_COPY_TO, $this->getStore()->getStoreId());
        if (!empty($data)) {
            return explode(',', $data);
        }
        return false;
    }

    /**
     * @return mixed
     * @since 2.0.0
     */
    public function getCopyMethod()
    {
        return $this->getConfigValue(self::XML_PATH_EMAIL_COPY_METHOD, $this->getStore()->getStoreId());
    }

    /**
     * @return mixed
     * @since 2.0.0
     */
    public function getGuestTemplateId()
    {
        return $this->getConfigValue(self::XML_PATH_EMAIL_GUEST_TEMPLATE, $this->getStore()->getStoreId());
    }

    /**
     * @return mixed
     * @since 2.0.0
     */
    public function getTemplateId()
    {
        return $this->getConfigValue(self::XML_PATH_EMAIL_TEMPLATE, $this->getStore()->getStoreId());
    }

    /**
     * @return mixed
     * @since 2.0.0
     */
    public function getEmailIdentity()
    {
        return $this->getConfigValue(self::XML_PATH_EMAIL_IDENTITY, $this->getStore()->getStoreId());
    }
}
