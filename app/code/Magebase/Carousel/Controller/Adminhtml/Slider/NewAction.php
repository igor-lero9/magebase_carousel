<?php
namespace Magebase\Carousel\Controller\Adminhtml\Slider;

/**
 * Class NewAction
 * @package Magebase\Carousel\Controller\Adminhtml\Slider
 */
class NewAction extends \Magento\Backend\App\Action
{
    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magebase_Carousel::save');
    }

    /**
     * Forward to edit
     *
     * @return \Magento\Backend\Model\View\Result\Forward
     */
    public function execute()
    {
        $this->_forward('edit');
    }
}