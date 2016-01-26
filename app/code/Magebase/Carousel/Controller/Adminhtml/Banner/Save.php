<?php

namespace Magebase\Carousel\Controller\Adminhtml\Banner;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\TestFramework\ErrorLog\Logger;
use Magento\Backend\App\Action;
/**
 * Save Banner action.
 * @category Magestore
 * @package  Magestore_Bannerslider
 * @module   Bannerslider
 * @author   Magestore Developer
 */
class Save extends \Magento\Backend\App\Action
{

    protected $dataProcessor;
    /**
     * @param Action\Context $context
     */
    public function __construct(
        Action\Context $context,
        \Magento\Cms\Controller\Adminhtml\Page\PostDataProcessor $dataProcessor
    )
    {
        $this->dataProcessor = $dataProcessor;
        parent::__construct($context);
    }
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
       // $resultRedirect = $this->resultRedirectFactory->create();

        if ($data) {
            $data = $this->dataProcessor->filter($data);

            $model = $this->_objectManager->create('Magebase\Carousel\Model\Banner');
            //$storeViewId = $this->getRequest()->getParam('store');

            if ($id = $this->getRequest()->getParam('banner_id')) {
                $model->load($id);
            }

            // save image data and remove from data array
            if (isset($data['image'])) {
                $imageData = $data['image'];
                unset($data['image']);
            } else {
                $imageData = array();
            }

            $model->addData($data);


            if (!$this->dataProcessor->validate($data)) {
                $this->_redirect('*/*/edit', ['news_id' => $model->getId(), '_current' => true]);
                return;
            }

            $imageHelper = $this->_objectManager->get('Magebase\Carousel\Helper\Data');
            if (isset($imageData['delete']) && $model->getImage()) {
                $imageHelper->removeImage($model->getImage());
                $model->setImage(null);
            }

            $imageFile = $imageHelper->uploadImage('image');
            if ($imageFile) {
                $data['image'] = $imageFile;
            }

            /** @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate */
            $localeDate = $this->_objectManager->get('Magento\Framework\Stdlib\DateTime\TimezoneInterface');
            $data['start_time'] = $localeDate->date($data['start_time'])->setTimezone(new \DateTimeZone('UTC'))->format('Y-m-d H:i');
            $data['end_time'] = $localeDate->date($data['end_time'])->setTimezone(new \DateTimeZone('UTC'))->format('Y-m-d H:i');
            $model->setData($data);

            //  ->setStoreViewId($storeViewId);
            try {
                $model->save();

                $this->messageManager->addSuccess(__('The banner has been saved.'));
                $this->_getSession()->setFormData(false);

                if ($this->getRequest()->getParam('back') === 'edit') {
                    $this->_redirect(
                        '*/*/new',
                        ['banner_id' => $model->getId(), '_current' => true, 'save_and_close' => $this->getRequest()->getParam('save_and_close')]
                    );
                    return;

                } elseif ($this->getRequest()->getParam('back') === 'new') {
                    $this->_redirect('*/*/new', ['banner_id' => $model->getId(), '_current' => true]);
                    return;
                }

                $this->_redirect('*/*/');
                return;

            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                $this->messageManager->addException($e, __('Something went wrong while saving the banner.'));
            }

            $this->_getSession()->setFormData($data);


            $this->_redirect('*/*/edit', ['banner_id' => $this->getRequest()->getParam('banner_id')]);
            return;
        }

        $this->_redirect('*/*/');
        return;
    }
}
