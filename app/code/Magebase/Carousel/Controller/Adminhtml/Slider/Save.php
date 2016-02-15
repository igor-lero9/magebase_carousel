<?php

namespace Magebase\Carousel\Controller\Adminhtml\Slider;

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
    protected $_jsHelper;
    protected $dataProcessor;
     /**
     * @param Action\Context $context
     */
    public function __construct(
        Action\Context $context,
        \Magento\Cms\Controller\Adminhtml\Page\PostDataProcessor $dataProcessor,
        \Magento\Backend\Helper\Js $jsHelper
    )
    {
        parent::__construct($context);
        $this->dataProcessor = $dataProcessor;
        $this->_jsHelper = $jsHelper;
    }
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();

        if ($data) {
            $data = $this->dataProcessor->filter($data);

            $model = $this->_objectManager->create('Magebase\Carousel\Model\Slider');

            if ($id = $this->getRequest()->getParam('slider_id')) {
                $model->load($id);
            }


            if (!$this->dataProcessor->validate($data)) {
                $this->_redirect('*/*/edit', ['slider_id' => $model->getId(), '_current' => true]);
                return;
            }
            $model->addData($data);

            if(isset($data['bannerIds'])){
                $bannerIds = [];
                if($data['bannerIds'] != ''){
                    $bannerGridInputData = explode("&", $data['bannerIds']);
                    foreach ($bannerGridInputData as $key => $value) {
                        $bannerIds[] = $value[0];
                    }
                }
                $model['items_id'] = $bannerIds;
            }

            try {
                $model->save();

                $this->messageManager->addSuccess(__('The banner has been saved.'));
                $this->_getSession()->setFormData(false);

                if ($this->getRequest()->getParam('back') === 'edit') {
                    $this->_redirect(
                        '*/*/new',
                        ['slider_id' => $model->getId(), '_current' => true, 'save_and_close' => $this->getRequest()->getParam('save_and_close')]
                    );
                    return;

                } elseif ($this->getRequest()->getParam('back') === 'new') {
                    $this->_redirect('*/*/new', ['slider_id' => $model->getId(), '_current' => true]);
                    return;
                }

                $this->_redirect('*/*/');
                return;

            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                $this->messageManager->addException($e, __('Something went wrong while saving the banner.'));
            }

            $this->_getSession()->setFormData($data);


            $this->_redirect('*/*/edit', ['slider_id' => $this->getRequest()->getParam('slider_id')]);
            return;
        }

        $this->_redirect('*/*/');
        return;
    }
}
