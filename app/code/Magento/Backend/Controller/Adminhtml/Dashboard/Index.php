<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Backend\Controller\Adminhtml\Dashboard;

/**
 * Class \Magento\Backend\Controller\Adminhtml\Dashboard\Index
 *
 * @since 2.0.0
 */
class Index extends \Magento\Backend\Controller\Adminhtml\Dashboard
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     * @since 2.0.0
     */
    protected $resultPageFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @since 2.0.0
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Page
     * @since 2.0.0
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Magento_Backend::dashboard');
        $resultPage->addBreadcrumb(__('Dashboard'), __('Dashboard'));
        $resultPage->getConfig()->getTitle()->prepend(__('Dashboard'));

        return $resultPage;
    }
}
