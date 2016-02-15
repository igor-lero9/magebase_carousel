<?php

namespace Magebase\Carousel\Controller\Adminhtml\Slider;

/**
 * Class Delete
 * @package Magebase\Carousel\Controller\Adminhtml\Slider
 */
class Delete extends \Magento\Backend\App\Action
{
    /**
     * @return bool
     */
    protected function isAllowed()
    {
        return $this->_authorization->isAllowed('Magebase_Carousel::delete');
    }

    /**
     * Execute
     */
    public function execute()
    {
        /**
         * Get Slider ID
         */
        $sliderId = $this->getRequest()->getParam('slider_id');

        if ($sliderId) {
            try {
                $slider = $this->_objectManager->create('Magebase\Carousel\Model\Slider');
                $slider->load($sliderId);
                $slider->delete();
                $this->messageManager->addSuccess(__('Banner deleted successfully'));

                /**
                 * Redirect back to banner list
                 */
                $this->_redirect('*/*/');
                return;
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                /**
                 * Redirect back to edit page if there is and error
                 */
                $this->_redirect('*/*/edit', ['slider_id' => $sliderId]);
                return;
            }
        }

        /**
         * If there is no banner throw an error and redirect
         */

        $this->messageManager->addError(__('There is no banner to delete'));

        /**
         * Redirect back to list
         */
        $this->_redirect('*/*/');
        return;

    }

}