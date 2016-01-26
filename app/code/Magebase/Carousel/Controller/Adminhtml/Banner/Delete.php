<?php

namespace Magebase\Carousel\Controller\Adminhtml\Banner;

/**
 * Class Delete
 * @package Magebase\Carousel\Controller\Adminhtml\Banner
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
        $bannerId = $this->getRequest()->getParam('banner_id');
        /**
         * Check if banner ID is present
         */
        if ($bannerId) {
            try {
                /**
                 * Load banner first and then delete
                 */
                $banner = $this->_objectManager->create('Magebase\Carousel\Model\Banner');
                $banner->load($bannerId);
                $banner->delete();
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
                $this->_redirect('*/*/edit', ['banner_id' => $bannerId]);
                return;
            }
        }

        /**
         * If there is no banner throw an error and redirect
         */

        $this->messageManager->addError(__('There is no banner to delete'));

        /**
         * Redirect back to list page
         */
        $this->_redirect('*/*/');
        return;

    }

}