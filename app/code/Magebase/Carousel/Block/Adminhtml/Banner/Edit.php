<?php

namespace Magebase\Carousel\Block\Adminhtml\Banner;

/**
 * Class Edit
 * @package Magebase\Carousel\Block\Adminhtml\Banner
 *
 */
class Edit extends \Magento\Backend\Block\Widget\Form\Container{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * Construct
     */
    protected function _construct(){
        $this->_objectId = 'banner_id';
        $this->_blockGroup = 'Magebase_Carousel';
        $this->_controller = 'adminhtml_banner';

        parent::_construct();

        $this->buttonList->update('save', 'label', __('Save Banner'));
        $this->buttonList->update('delete', 'label', __('Delete Banner'));
        $this->buttonList->add(
            'save_and_continue',
            [
                'label' => __('Save and Continue Edit'),
                'class' => 'save',
                'data_attribute' => [
                    'mage-init' => [
                        'button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form'],
                    ],
                ]
            ],
            -100
        );
        $this->buttonList->add(
            'save_and_close',
            [
                'label' => __('Save and Close'),
                'class' => 'save_and_close',
                'data_attribute' => [
                    'mage-init' => [
                        'button' => ['event' => 'saveAndCloseWindow', 'target' => '#edit_form'],
                    ],
                ]
            ],
            -100
        );
    }

    /**
     * @return string
     *
     * Save and Continue URL
     */
    protected function _getSaveAndContinueUrl()
    {
        return $this->getUrl('*/*/save', ['_current' => true, 'back' => 'edit', 'tab' => '{{tab_id}}', 'banner_id' => $this->getRequest()->getParam('banner_id')]);
    }

}
