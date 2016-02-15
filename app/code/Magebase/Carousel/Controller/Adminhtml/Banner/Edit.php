<?php

namespace Magebase\Carousel\Controller\Adminhtml\Banner;

/**
 * Class Edit
 * @package Magebase\Carousel\Controller\Adminhtml\Banner
 */
abstract class Edit extends \Magento\Backend\App\Action
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $registry;
        parent::__construct($context);
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magebase_Carousel::save');
    }

    /**
     * @return \Magento\Framework\View\Result\Page
     */
    protected function _initAction()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Magebase_Carousel::carousel')
            ->addBreadcrumb(__('Banners'), __('Banners'))
            ->addBreadcrumb(__('Manage Banner'), __('Manage Banner'));
        return $resultPage;
    }

    /**
     * @return $this|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {

        $id = $this->getRequest()->getParam('banner_id');
        $model = $this->_objectManager->create('Magebase\Carousel\Model\Banner');

        if ($id) {
            $model->load($id);
            if (!$model->getId($id)) {
                $this->messageManager->addError(__('This Banner no longer exists.'));

                $resultRedirect = $this->resultRedirectFactory->create();

                return $resultRedirect->setPath('*/*/');
            }
        }

        $data = $this->_objectManager->get('Magento\Backend\Model\Session')->getFormData(true);

        if (!empty($data)) {
            $model->setData($data);
        }

        $this->_coreRegistry->register('banner', $model);

        $resultPage = $this->_initAction();

        return $resultPage;
    }

}