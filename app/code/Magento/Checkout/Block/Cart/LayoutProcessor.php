<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Checkout\Block\Cart;

/**
 * Class \Magento\Checkout\Block\Cart\LayoutProcessor
 *
 * @since 2.0.0
 */
class LayoutProcessor implements \Magento\Checkout\Block\Checkout\LayoutProcessorInterface
{
    /**
     * @var \Magento\Checkout\Block\Checkout\AttributeMerger
     * @since 2.0.0
     */
    protected $merger;

    /**
     * @var \Magento\Directory\Model\ResourceModel\Country\Collection
     * @since 2.0.0
     */
    protected $countryCollection;

    /**
     * @var \Magento\Directory\Model\ResourceModel\Region\Collection
     * @since 2.0.0
     */
    protected $regionCollection;

    /**
     * @var \Magento\Customer\Api\Data\AddressInterface
     * @since 2.0.0
     */
    protected $defaultShippingAddress = null;

    /**
     * @var \Magento\Directory\Model\TopDestinationCountries
     * @since 2.2.0
     */
    private $topDestinationCountries;

    /**
     * @param \Magento\Checkout\Block\Checkout\AttributeMerger $merger
     * @param \Magento\Directory\Model\ResourceModel\Country\Collection $countryCollection
     * @param \Magento\Directory\Model\ResourceModel\Region\Collection $regionCollection
     * @param \Magento\Directory\Model\TopDestinationCountries $topDestinationCountries
     * @codeCoverageIgnore
     * @since 2.0.0
     */
    public function __construct(
        \Magento\Checkout\Block\Checkout\AttributeMerger $merger,
        \Magento\Directory\Model\ResourceModel\Country\Collection $countryCollection,
        \Magento\Directory\Model\ResourceModel\Region\Collection $regionCollection,
        \Magento\Directory\Model\TopDestinationCountries $topDestinationCountries = null
    ) {
        $this->merger = $merger;
        $this->countryCollection = $countryCollection;
        $this->regionCollection = $regionCollection;
        $this->topDestinationCountries = $topDestinationCountries ?:
            \Magento\Framework\App\ObjectManager::getInstance()
                ->get(\Magento\Directory\Model\TopDestinationCountries::class);
    }

    /**
     * Show City in Shipping Estimation
     *
     * @return bool
     * @codeCoverageIgnore
     * @since 2.0.0
     */
    protected function isCityActive()
    {
        return false;
    }

    /**
     * Show State in Shipping Estimation
     *
     * @return bool
     * @codeCoverageIgnore
     * @since 2.0.0
     */
    protected function isStateActive()
    {
        return false;
    }

    /**
     * Process js Layout of block
     *
     * @param array $jsLayout
     * @return array
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @since 2.0.0
     */
    public function process($jsLayout)
    {
        $elements = [
            'city' => [
                'visible' => $this->isCityActive(),
                'formElement' => 'input',
                'label' => __('City'),
                'value' =>  null
            ],
            'country_id' => [
                'visible' => true,
                'formElement' => 'select',
                'label' => __('Country'),
                'options' => [],
                'value' => null
            ],
            'region_id' => [
                'visible' => true,
                'formElement' => 'select',
                'label' => __('State/Province'),
                'options' => [],
                'value' => null
            ],
            'postcode' => [
                'visible' => true,
                'formElement' => 'input',
                'label' => __('Zip/Postal Code'),
                'value' => null
            ]
        ];

        if (!isset($jsLayout['components']['checkoutProvider']['dictionaries'])) {
            $jsLayout['components']['checkoutProvider']['dictionaries'] = [
                'country_id' => $this->countryCollection->loadByStore()->setForegroundCountries(
                    $this->topDestinationCountries->getTopDestinations()
                )->toOptionArray(),
                'region_id' => $this->regionCollection->addAllowedCountriesFilter()->toOptionArray(),
            ];
        }
        if (isset($jsLayout['components']['block-summary']['children']['block-shipping']['children']
            ['address-fieldsets']['children'])
        ) {
            $fieldSetPointer = &$jsLayout['components']['block-summary']['children']['block-shipping']
            ['children']['address-fieldsets']['children'];
            $fieldSetPointer = $this->merger->merge($elements, 'checkoutProvider', 'shippingAddress', $fieldSetPointer);
            $fieldSetPointer['region_id']['config']['skipValidation'] = true;
        }
        return $jsLayout;
    }
}
