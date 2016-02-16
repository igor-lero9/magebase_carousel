<?php

namespace Magebase\Carousel\Controller\Adminhtml\Slider;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Index
 * @package Magebase\Carousel\Controller\Adminhtml\Slider
 */
class Index extends \Magento\Backend\App\Action{
    /**
     * ADMIN_RESOURCE
     */
    const ADMIN_RESOURCE = 'Magebase_Carousel::carousel_sliders';

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    )
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute(){

        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Magebase_Carousel::carousel');
        $resultPage->addBreadcrumb(__('Sliders'), __('Sliders'));
        $resultPage->addBreadcrumb(__('Manage Sliders'), __('Manage Sliders'));
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Sliders'));

        return $resultPage;
    }

}