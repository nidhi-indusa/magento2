<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Quote\Model\GuestCart;

use Magento\Quote\Model\QuoteIdMaskFactory;
use Magento\Quote\Api\GuestCartTotalManagementInterface;

/**
 * @inheritDoc
 * @since 2.0.0
 */
class GuestCartTotalManagement implements GuestCartTotalManagementInterface
{
    /**
     * @var \Magento\Quote\Api\CartTotalManagementInterface
     * @since 2.0.0
     */
    protected $cartTotalManagement;

    /**
     * @var QuoteIdMaskFactory
     * @since 2.0.0
     */
    protected $quoteIdMaskFactory;

    /**
     * @param \Magento\Quote\Api\CartTotalManagementInterface $cartTotalManagement
     * @param QuoteIdMaskFactory $quoteIdMaskFactory
     * @since 2.0.0
     */
    public function __construct(
        \Magento\Quote\Api\CartTotalManagementInterface $cartTotalManagement,
        QuoteIdMaskFactory $quoteIdMaskFactory
    ) {
        $this->cartTotalManagement = $cartTotalManagement;
        $this->quoteIdMaskFactory = $quoteIdMaskFactory;
    }

    /**
     * {@inheritDoc}
     * @since 2.0.0
     */
    public function collectTotals(
        $cartId,
        \Magento\Quote\Api\Data\PaymentInterface $paymentMethod,
        $shippingCarrierCode = null,
        $shippingMethodCode = null,
        \Magento\Quote\Api\Data\TotalsAdditionalDataInterface $additionalData = null
    ) {
        $quoteIdMask = $this->quoteIdMaskFactory->create()->load($cartId, 'masked_id');
        return $this->cartTotalManagement->collectTotals(
            $quoteIdMask->getQuoteId(),
            $paymentMethod,
            $shippingCarrierCode,
            $shippingMethodCode,
            $additionalData
        );
    }
}
