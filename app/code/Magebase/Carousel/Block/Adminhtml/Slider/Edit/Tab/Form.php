<?php

namespace Magebase\Carousel\Block\Adminhtml\Slider\Edit\Tab;

/**
 *
 * Class Form
 * @package Magebase\Carousel\Block\Adminhtml\Slider\Edit\Tab
 *
 */
class Form extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * @var \Magento\Framework\DataObject
     */
    protected $_objectFactory;
    /**
     * @var \Magebase\Carousel\Model\Slider
     */
    protected $_slider;
    /**
     * @var \Magento\Cms\Model\ResourceModel\Block\CollectionFactory
     */
    protected $blockColFactory;
    /**
     * @var
     */
    protected $blockOptions;

    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Framework\DataObject $objectFactory
     * @param \Magebase\Carousel\Model\Slider $slider
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param \Magento\Cms\Model\ResourceModel\Block\CollectionFactory $blockColFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Framework\DataObject $objectFactory,
        \Magebase\Carousel\Model\Slider $slider,
        \Magento\Store\Model\System\Store $systemStore,
        \Magento\Cms\Model\ResourceModel\Block\CollectionFactory $blockColFactory,
        array $data = []
    )
    {
        $this->_objectFactory = $objectFactory;
        $this->_slider = $slider;
        $this->blockColFactory = $blockColFactory;
        $this->_systemStore = $systemStore;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareForm()
    {

        $model = $this->_coreRegistry->registry('slider');

        $this->blockOptions = $this->blockColFactory->create();
        $this->blockOptions = $this->blockOptions->toOptionArray();
        $isElementDisabled = true;

        $form = $this->_formFactory->create();


        /*
        * declare dependence
        */
        // dependence block
        $dependenceBlock = $this->getLayout()->createBlock(
            'Magento\Backend\Block\Widget\Form\Element\Dependence'
        );

        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('Slider Information')]
        );

        if ($model->getId()) {
            $fieldset->addField('slider_id', 'hidden', ['name' => 'slider_id']);
        }

        $fieldset->addField(
            'title',
            'text',
            [
                'name' => 'title',
                'label' => __('Slider Title'),
                'title' => __('Slider Title'),
                'required' => true,
                'class' => 'required-entry',
            ]
        );
        $fieldset->addField(
            'slider_status',
            'select',
            [
                'label' => __('Slider Status'),
                'title' => __('Slider Status'),
                'name' => 'slider_status',
                'options' => ['1' => __('Enabled'), '0' => __('Disabled')]
            ]
        );

        if (!$this->_storeManager->isSingleStoreMode()) {
            $field = $fieldset->addField(
                'store_id',
                'multiselect',
                [
                    'name' => 'stores[]',
                    'label' => __('Store View'),
                    'title' => __('Store View'),
                    'required' => true,
                    'values' => $this->_systemStore->getStoreValuesForForm(false, true)
                ]
            );
            $renderer = $this->getLayout()->createBlock(
                'Magento\Backend\Block\Store\Switcher\Form\Renderer\Fieldset\Element'
            );
            $field->setRenderer($renderer);
        } else {
            $fieldset->addField(
                'store_id',
                'hidden',
                ['name' => 'stores[]', 'value' => $this->_storeManager->getStore(true)->getId()]
            );
            $model->setStoreId($this->_storeManager->getStore(true)->getId());
        }
        $fieldset->addField(
            'banner_number',
            'text',
            [
                'name' => 'banner_number',
                'label' => __('Number of banners to show'),
                'title' => __('Number of banners to show'),
                'required' => true
            ]
        );

        $fieldset->addField(
            'loop',
            'select',
            [
                'label' => __('Intinite rotation'),
                'name' => 'loop',
                'options' => $this->getYesNo(),
            ]
        );

        $fieldset->addField(
            'auto_width',
            'select',
            [
                'label' => __('Auto Width'),
                'name' => 'auto_width',
                'options' => $this->getYesNo(),
            ]
        );

        $fieldset->addField(
            'start_position',
            'text',
            [
                'name' => 'start_position',
                'label' => __('Banner number Slider should start from'),
                'title' => __('Banner number Slider should start from'),
                'required' => true
            ]
        );

        $fieldset->addField(
            'nav',
            'select',
            [
                'label' => __('Enable Navigation'),
                'name' => 'nav',
                'options' => $this->getYesNo(),
            ]
        );


        $fieldset->addField(
            'dots',
            'select',
            [
                'label' => __('Enable Dots'),
                'name' => 'dots',
                'options' => $this->getYesNo(),
            ]
        );

        $fieldset->addField(
            'auto_play',
            'select',
            [
                'label' => __('Enable Autoplay'),
                'name' => 'auto_play',
                'options' => $this->getYesNo(),
            ]
        );

        $fieldset->addField(
            'autoplay_timeout',
            'text',
            [
                'name' => 'autoplay_timeout',
                'label' => __('Please enter autoplay timeout time'),
                'title' => __('Please enter autoplay timeout time'),
                'required' => true
            ]
        );
        $fieldset->addField(
            'autoplay_speed',
            'text',
            [
                'name' => 'autoplay_speed',
                'label' => __('Please enter autoplay speed time'),
                'title' => __('Please enter autoplay speed time'),
                'required' => true
            ]
        );
        $fieldset->addField(
            'animate_in',
            'select',
            [
                'label' => __('Please select Start Animation'),
                'name' => 'animate_in',
                'values' => [
                    [
                        'value' => 'fadeIn',
                        'label' => __('FadeIn'),
                    ],
                    [
                        'value' => 'slideInLeft',
                        'label' => __('SlideInLeft'),
                    ]
                ],
            ]
        );
        $fieldset->addField(
            'animate_out',
            'select',
            [
                'label' => __('Please select End Animation'),
                'name' => 'animate_out',
                'values' => [
                    [
                        'value' => 'fadeOut',
                        'label' => __('FadeOut'),
                    ],
                    [
                        'value' => 'slideOutRight',
                        'label' => __('SlideOutRight'),
                    ]
                ],
            ]
        );
        $include_block = $fieldset->addField(
            'include_block',
            'select',
            [
                'label' => __('Include Static Blocks'),
                'title' => __('Slider Type'),
                'name' => 'include_block',
                'options' => $this->getYesNo(),
                'disabled' => false,
            ]
        );
        $first_block = $fieldset->addField(
            'first_block',
            'select',
            [
                'name' => 'first_block',
                'label' => __('CMS Block 1'),
                'title' => __('CMS Block 1'),
                'values' => $this->blockOptions
            ]
        );
        $second_block = $fieldset->addField(
            'second_block',
            'select',
            [
                'name' => 'second_block',
                'label' => __('CMS Block 2'),
                'title' => __('CMS Block 2'),
                'values' => $this->blockOptions
            ]
        );

        $form->setHtmlIdPrefix($this->_slider->getFormFieldHtmlIdPrefix());


        $form->setValues($model->getData());
        $this->setForm($form);
        $this->setChild('form_after', $this->getLayout()->createBlock('Magento\Backend\Block\Widget\Form\Element\Dependence')
            ->addFieldMap($include_block->getHtmlId(), $include_block->getName())
            ->addFieldMap($first_block->getHtmlId(), $first_block->getName())
            ->addFieldMap($second_block->getHtmlId(), $second_block->getName())
            ->addFieldDependence(
                $first_block->getName(),
                $include_block->getName(),
                '1'
            )
            ->addFieldDependence(
                $second_block->getName(),
                $include_block->getName(),
                '1'
            ));

        return parent::_prepareForm();
    }

    /**
     * @return mixed
     */
    public function getSlider()
    {
        return $this->_coreRegistry->registry('slider');
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    public function getPageTitle()
    {
        return $this->getBanner()->getId()
            ? __("Edit Slider '%1'", $this->escapeHtml($this->getBanner()->getName())) : __('New Slider');
    }

    /**
     * Prepare label for tab.
     *
     * @return string
     */
    public function getTabLabel()
    {
        return __('Slider Information');
    }

    /**
     * Prepare title for tab.
     *
     * @return string
     */
    public function getTabTitle()
    {
        return __('Slider Information');
    }


    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * @return array Statuses
     */
    public static function getAvailableStatuses()
    {
        return [
            0 => __('Disabled'), 1 => __('Enabled')
        ];
    }

    /**
     * @return array
     */
    public static function getYesNo()
    {
        return [
            0 => __('No'), 1 => __('Yes')
        ];
    }
}