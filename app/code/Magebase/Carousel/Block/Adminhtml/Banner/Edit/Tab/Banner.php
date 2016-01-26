<?php

namespace Magebase\Carousel\Block\Adminhtml\Banner\Edit\Tab;

/**
 * Class Banner
 * @package Magebase\Carousel\Block\Adminhtml\Banner\Edit\Tab
 */

class Banner extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface {
    /**
     * @var \Magento\Framework\DataObject
     */
    protected $_objectFactory;
    /**
     * @var \Magebase\Carousel\Model\Banner
     */
    protected $_banner;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Framework\DataObject $objectFactory
     * @param \Magebase\Carousel\Model\Banner $banner
     * @param \Magebase\Carousel\Model\Slider $slider
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Framework\DataObject $objectFactory,
        \Magebase\Carousel\Model\Banner $banner,
        \Magebase\Carousel\Model\Slider $slider,
        array $data = []
    ){
        $this->_objectFactory = $objectFactory;
        $this->_banner = $banner;
        $this->_sliderFactory = $slider;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareForm(){

        $model = $this->_coreRegistry->registry('banner');

        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix($this->_banner->getFormFieldHtmlIdPrefix());

        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('Banner Information')]
        );

        if($model->getId()){
            $fieldset->addField('banner_id', 'hidden', ['name' => 'banner_id']);
        }

        $fieldset->addField(
            'name',
            'text',
            ['name' => 'name', 'label' => __('Banner Name'), 'title' => __('Banner Name'), 'required' => true]
        );
        $fieldset->addField(
            'status',
            'select',
            [
                'label' => __('Status'),
                'title' => __('Status'),
                'name' => 'status',
                'required' => true,
                'options' => ['1' => __('Enabled'), '0' => __('Disabled')]
            ]
        );
        if (!$model->getId()) {
            $model->setData('status', '1');
        }
        $type = $fieldset->addField(
            'type',
            'select',
            [
                'label' => __('Status'),
                'title' => __('Status'),
                'name' => 'type',
                'required' => true,
                'options' => ['1' => __('Image'), '0' => __('Video')]
            ]
        );
        $imageField = $fieldset->addField(
            'image',
            'image',
            [
                'title' => __('Banner Image'),
                'label' => __('Banner Image'),
                'name' => 'image',
                'note' => 'Allow image type: jpg, jpeg, gif, png',
            ]
        );
        $imageAltText  = $fieldset->addField(
            'image_alt',
            'text',
            [
                'title' => __('Alt Text'),
                'label' => __('Alt Text'),
                'name' => 'image_alt',
                'note' => 'Used for SEO',
            ]
        );
        $imageUrl = $fieldset->addField(
            'url',
            'text',
            [
                'title' => __('URL'),
                'label' => __('URL'),
                'name' => 'url',
            ]
        );
        $imageTarget = $fieldset->addField(
            'target',
            'select',
            [
                'label' => __('Target'),
                'name' => 'target',
                'values' => [
                    [
                        'value' => \Magebase\Carousel\Model\Banner::BANNER_TARGET_SELF,
                        'label' => __('New Window with Browser Navigation'),
                    ],
                    [
                        'value' => \Magebase\Carousel\Model\Banner::BANNER_TARGET_PARENT,
                        'label' => __('Parent Window with Browser Navigation'),
                    ],
                    [
                        'value' => \Magebase\Carousel\Model\Banner::BANNER_TARGET_BLANK,
                        'label' => __('New Window without Browser Navigation'),
                    ],
                ],
            ]
        );
         $videoUrl = $fieldset->addField(
            'video',
            'text',
            [
                'title' => __('Video Url'),
                'label' => __('Video Url'),
                'name' => 'video',
            ]
        );

        $dateFormat = $this->_localeDate->getDateFormat(\IntlDateFormatter::SHORT);
        $timeFormat = $this->_localeDate->getTimeFormat(\IntlDateFormatter::SHORT);

        $style = 'color: #000;background-color: #fff; font-weight: bold; font-size: 13px;';

        $fieldset->addField(
            'start_time',
            'date',
            [
                'name' => 'start_time',
                'label' => __('Starting time'),
                'title' => __('Starting time'),
                'required' => true,
                'readonly' => true,
                'style' => $style,
                'class' => 'required-entry',
                'date_format' => $dateFormat,
                'time_format' => $timeFormat,
                'note' => $this->_localeDate->getDateTimeFormat(\IntlDateFormatter::SHORT),
            ]
        );

        $fieldset->addField(
            'end_time',
            'date',
            [
                'name' => 'end_time',
                'label' => __('Ending time'),
                'title' => __('Ending time'),
                'required' => true,
                'readonly' => true,
                'style' => $style,
                'class' => 'required-entry',
                'date_format' => $dateFormat,
                'time_format' => $timeFormat,
                'note' => $this->_localeDate->getDateTimeFormat(\IntlDateFormatter::SHORT)
            ]
        );
        $fieldset->addField(
            'order_banner',
            'text',
            [
                'name' => 'order_banner',
                'label' => __('Banner Order'),
                'title' => __('Banner Order'),
                'required' => true
            ]
        );

        $form->setValues($model->getData());
        $this->setForm($form);
        $this->setChild('form_after', $this->getLayout()->createBlock('Magento\Backend\Block\Widget\Form\Element\Dependence')
            ->addFieldMap($type->getHtmlId(), $type->getName())
            ->addFieldMap($imageField->getHtmlId(), $imageField->getName())
            ->addFieldMap($imageAltText->getHtmlId(), $imageAltText->getName())
            ->addFieldMap($imageUrl->getHtmlId(), $imageUrl->getName())
            ->addFieldMap($imageTarget->getHtmlId(), $imageTarget->getName())
            ->addFieldMap($videoUrl->getHtmlId(), $videoUrl->getName())
            ->addFieldDependence(
                $imageField->getName(),
                $type->getName(),
                '1'
            )
            ->addFieldDependence(
                $imageAltText->getName(),
                $type->getName(),
                '1'
            )
            ->addFieldDependence(
                $imageUrl->getName(),
                $type->getName(),
                '1'
            )
            ->addFieldDependence(
                $imageTarget->getName(),
                $type->getName(),
                '1'
            )
            ->addFieldDependence(
                $videoUrl->getName(),
                $type->getName(),
                '0'
            )
            );
        return parent::_prepareForm();
    }

    /**
     * @return mixed
     */
    public function getBanner()
    {
        return $this->_coreRegistry->registry('banner');
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    public function getPageTitle()
    {
        return $this->getBanner()->getId()
            ? __("Edit Banner '%1'", $this->escapeHtml($this->getBanner()->getName())) : __('New Banner');
    }

    /**
     * Prepare label for tab.
     *
     * @return string
     */
    public function getTabLabel()
    {
        return __('Banner Information');
    }

    /**
     * Prepare title for tab.
     *
     * @return string
     */
    public function getTabTitle()
    {
        return __('Banner Information');
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
}