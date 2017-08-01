<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Review\Block;

use Magento\Catalog\Model\Product;
use Magento\Customer\Model\Context;
use Magento\Customer\Model\Url;
use Magento\Review\Model\ResourceModel\Rating\Collection as RatingCollection;

/**
 * Review form block
 *
 * @api
 * @author      Magento Core Team <core@magentocommerce.com>
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @since 2.0.0
 */
class Form extends \Magento\Framework\View\Element\Template
{
    /**
     * Review data
     *
     * @var \Magento\Review\Helper\Data
     * @since 2.0.0
     */
    protected $_reviewData = null;

    /**
     * Catalog product model
     *
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     * @since 2.0.0
     */
    protected $productRepository;

    /**
     * Rating model
     *
     * @var \Magento\Review\Model\RatingFactory
     * @since 2.0.0
     */
    protected $_ratingFactory;

    /**
     * @var \Magento\Framework\Url\EncoderInterface
     * @since 2.0.0
     */
    protected $urlEncoder;

    /**
     * Message manager interface
     *
     * @var \Magento\Framework\Message\ManagerInterface
     * @since 2.0.0
     */
    protected $messageManager;

    /**
     * @var \Magento\Framework\App\Http\Context
     * @since 2.0.0
     */
    protected $httpContext;

    /**
     * @var \Magento\Customer\Model\Url
     * @since 2.0.0
     */
    protected $customerUrl;

    /**
     * @var array
     * @since 2.0.0
     */
    protected $jsLayout;

    /**
     * @var \Magento\Framework\Serialize\Serializer\Json
     * @since 2.2.0
     */
    private $serializer;

    /**
     * Form constructor.
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Url\EncoderInterface $urlEncoder
     * @param \Magento\Review\Helper\Data $reviewData
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \Magento\Review\Model\RatingFactory $ratingFactory
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param \Magento\Framework\App\Http\Context $httpContext
     * @param Url $customerUrl
     * @param array $data
     * @param \Magento\Framework\Serialize\Serializer\Json|null $serializer
     * @throws \RuntimeException
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     * @since 2.0.0
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Url\EncoderInterface $urlEncoder,
        \Magento\Review\Helper\Data $reviewData,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Review\Model\RatingFactory $ratingFactory,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\App\Http\Context $httpContext,
        \Magento\Customer\Model\Url $customerUrl,
        array $data = [],
        \Magento\Framework\Serialize\Serializer\Json $serializer = null
    ) {
        $this->urlEncoder = $urlEncoder;
        $this->_reviewData = $reviewData;
        $this->productRepository = $productRepository;
        $this->_ratingFactory = $ratingFactory;
        $this->messageManager = $messageManager;
        $this->httpContext = $httpContext;
        $this->customerUrl = $customerUrl;
        parent::__construct($context, $data);
        $this->jsLayout = isset($data['jsLayout']) ? $data['jsLayout'] : [];
        $this->serializer = $serializer ?: \Magento\Framework\App\ObjectManager::getInstance()
            ->get(\Magento\Framework\Serialize\Serializer\Json::class);
    }

    /**
     * Initialize review form
     *
     * @return void
     * @since 2.0.0
     */
    protected function _construct()
    {
        parent::_construct();

        $this->setAllowWriteReviewFlag(
            $this->httpContext->getValue(Context::CONTEXT_AUTH)
            || $this->_reviewData->getIsGuestAllowToWrite()
        );
        if (!$this->getAllowWriteReviewFlag()) {
            $queryParam = $this->urlEncoder->encode(
                $this->getUrl('*/*/*', ['_current' => true]) . '#review-form'
            );
            $this->setLoginLink(
                $this->getUrl(
                    'customer/account/login/',
                    [Url::REFERER_QUERY_PARAM_NAME => $queryParam]
                )
            );
        }

        $this->setTemplate('form.phtml');
    }

    /**
     * @return string
     * @since 2.0.0
     */
    public function getJsLayout()
    {
        return $this->serializer->serialize($this->jsLayout);
    }

    /**
     * Get product info
     *
     * @return \Magento\Catalog\Api\Data\ProductInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @since 2.0.0
     */
    public function getProductInfo()
    {
        return $this->productRepository->getById(
            $this->getProductId(),
            false,
            $this->_storeManager->getStore()->getId()
        );
    }

    /**
     * Get review product post action
     *
     * @return string
     * @since 2.0.0
     */
    public function getAction()
    {
        return $this->getUrl(
            'review/product/post',
            [
                '_secure' => $this->getRequest()->isSecure(),
                'id' => $this->getProductId(),
            ]
        );
    }

    /**
     * Get collection of ratings
     *
     * @return RatingCollection
     * @throws \Magento\Framework\Exception\LocalizedException
     * @since 2.0.0
     */
    public function getRatings()
    {
        return $this->_ratingFactory->create()->getResourceCollection()->addEntityFilter(
            'product'
        )->setPositionOrder()->addRatingPerStoreName(
            $this->_storeManager->getStore()->getId()
        )->setStoreFilter(
            $this->_storeManager->getStore()->getId()
        )->setActiveFilter(
            true
        )->load()->addOptionToItems();
    }

    /**
     * Return register URL
     *
     * @return string
     * @since 2.0.0
     */
    public function getRegisterUrl()
    {
        return $this->customerUrl->getRegisterUrl();
    }

    /**
     * Get review product id
     *
     * @return int
     * @since 2.0.0
     */
    protected function getProductId()
    {
        return $this->getRequest()->getParam('id', false);
    }
}
