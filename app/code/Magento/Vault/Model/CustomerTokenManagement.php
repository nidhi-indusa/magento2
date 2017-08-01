<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Vault\Model;

use Magento\Customer\Model\Session;
use Magento\Vault\Api\Data\PaymentTokenInterface;

/**
 * Class \Magento\Vault\Model\CustomerTokenManagement
 *
 * @since 2.1.0
 */
class CustomerTokenManagement
{
    /**
     * @var PaymentTokenManagement
     * @since 2.1.0
     */
    private $tokenManagement;

    /**
     * @var Session
     * @since 2.1.0
     */
    private $session;

    /**
     * CustomerTokenManagement constructor.
     * @param PaymentTokenManagement $tokenManagement
     * @param Session $session
     * @since 2.1.0
     */
    public function __construct(
        PaymentTokenManagement $tokenManagement,
        Session $session
    ) {
        $this->tokenManagement = $tokenManagement;
        $this->session = $session;
    }

    /**
     * Returns list of payment tokens for current customer session
     *
     * @return PaymentTokenInterface[]
     * @since 2.1.0
     */
    public function getCustomerSessionTokens()
    {
        $vaultPayments = [];

        $customerId = $this->session->getCustomerId();
        if (!$customerId) {
            return $vaultPayments;
        }

        return $this->tokenManagement->getVisibleAvailableTokens($customerId);
    }
}
