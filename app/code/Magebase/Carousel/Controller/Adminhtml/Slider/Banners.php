<?php
/**
 *
 */

namespace Magebase\Carousel\Controller\Adminhtml\Slider;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use \Magento\Framework\View\Result\LayoutFactory;

/**
 * Class Banners
 * @package Magebase\Carousel\Controller\Adminhtml\Slider
 */
class Banners extends \Magento\Backend\App\AbstractAction
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;
    /**
     * @var LayoutFactory
     */
    protected $resultLayoutFactory;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param LayoutFactory $resultLayoutFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        LayoutFactory $resultLayoutFactory
    )
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->resultLayoutFactory = $resultLayoutFactory;
    }

    /**
     * @return \Magento\Framework\View\Result\Layout
     */
    public function execute()
    {
        $resultLayout = $this->resultLayoutFactory->create();
        $resultLayout->getLayout()->getBlock('carousel.slider.edit.tab.banners')->setInBanner($this->getRequest()->getPost('banner', null));
        return $resultLayout;
    }
}
