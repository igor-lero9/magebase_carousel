<?php

namespace Magebase\Carousel\Block;

/**
 * Class Slider
 * @package Magebase\Carousel\Block
 */

class Slider extends \Magento\Framework\View\Element\Template implements \Magento\Widget\Block\BlockInterface

{
    /**
     * @var \Magebase\Carousel\Model\SliderFactory
     */
    protected $sliderFactory;

    /**
     * @var \Magebase\Carousel\Model\ResourceModel\Banner\Collection
     */
    protected $bannerCollectionFactory;

    /**
     * @var \Magebase\Carousel\Helper\Data
     */
    protected $bannersliderHelper;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $stdlibDateTime;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\Timezone
     */
    protected $stdTimezone;
    /**
     * @var \Magento\Framework\Json\Encoder
     */
    protected $encoder;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magebase\Carousel\Model\ResourceModel\Banner\Collection $bannerCollectionFactory
     * @param \Magebase\Carousel\Model\SliderFactory $sliderFactory
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $stdlibDateTime
     * @param \Magebase\Carousel\Helper\Data $bannersliderHelper
     * @param \Magento\Framework\Stdlib\DateTime\Timezone $_stdTimezone
     * @param \Magento\Framework\Json\Encoder $encoder
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magebase\Carousel\Model\ResourceModel\Banner\CollectionFactory $bannerCollectionFactory,
        \Magebase\Carousel\Model\SliderFactory $sliderFactory,
        \Magento\Framework\Stdlib\DateTime\DateTime $stdlibDateTime,
        \Magebase\Carousel\Helper\Data $bannersliderHelper,
        \Magento\Framework\Stdlib\DateTime\Timezone $_stdTimezone,
        \Magento\Framework\Json\Encoder $encoder,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->sliderFactory = $sliderFactory;
        $this->stdlibDateTime = $stdlibDateTime;
        $this->bannersliderHelper = $bannersliderHelper;
        $this->bannerCollectionFactory = $bannerCollectionFactory;
        $this->stdTimezone = $_stdTimezone;
        $this->encoder = $encoder;
    }

    /**
     * @return $this
     */
    public function _prepareLayout()
    {
        return parent::_prepareLayout();
    }

    /**
     * @return string
     */
    public function _toHtml()
    {
        $this->setTemplate('slider/main.phtml');
        return parent::_toHtml();
    }

    /**
     * @return Slider object
     */
    public function getSlider()
    {
        $slider = $this->sliderFactory->create()->load($this->getSliderId());
        return $slider;
    }

    /**
     * @return Json Object
     */
    public function getOptions()
    {
        $options = array();
        $sliderData = self::getSlider();
        if (isset($sliderData)) {
            $options['animateOut'] = self::getSlider()->getAnimateOut();
            $options['animateIn'] = self::getSlider()->getAnimateIn();
            if ($this->getBannerCount() > 1) {
                $options['loop'] = (int)self::getSlider()->getLoop();
            } else {
                $options['loop'] = 0;
            }
            $options['nav'] = (int)self::getSlider()->getNav();
            $options['dots'] = (int)self::getSlider()->getDots();
            $options['items'] = (int)1;
            if ($this->getBannerCount() > 1) {
                $options['autoplay'] = (int)self::getSlider()->getAutoPlay();
            } else {
                $options['autoplay'] = 0;
            }
            $options['autoplayTimeout'] = (int)self::getSlider()->getAutoplayTimeout();
            $options['autoplaySpeed'] = (int)self::getSlider()->getAutoplaySpeed();
            $options['startPosition'] = (int)self::getSlider()->getStartPosition();
            $options['video'] = (int)1;
            $options['lazyLoad'] = (int)1;

        }
        return $this->encoder->encode($options);
    }

    /**
     * @return Strind
     * SliderID
     */
    public function getId()
    {
        return self::getSlider()->getSliderId();
    }

    /**
     * @return mixed
     * Slider title
     */
    public function getSliderTitle()
    {
        return self::getSlider()->getTitle();
    }

    /**
     * @return mixed
     * Slider Status
     */
    public function getStatus()
    {
        return self::getSlider()->getSliderStatus();
    }

    /**
     * @return mixed
     * Bool Static Block
     */
    public function getSliderIncludeBlock()
    {
        return self::getSlider()->getIncludeBlock();
    }

    /**
     * @return string
     * Static Block ID
     */
    public function getSliderFirstBlock()
    {
        return $this->bannersliderHelper->getBlockContent(self::getSlider()->getFirstBlock());
    }

    /**
     * @return string
     * Static Block ID
     */
    public function getSliderSecondBlock()
    {
        return $this->bannersliderHelper->getBlockContent(self::getSlider()->getSecondBlock());
    }

    /**
     * @return $this Banner Collection
     */
    public function getBannerCollection()
    {
        $dateTimeNow = $this->stdTimezone->date()->format('Y-m-d H:i:s');
        $bannerCollection = $this->bannerCollectionFactory->create()
            ->getBannerRelationCollection($this->getSliderId())
            ->addFieldToFilter('start_time', ['lteq' => $dateTimeNow])
            ->addFieldToFilter('end_time', ['gteq' => $dateTimeNow])
            ->setOrder('order_banner', 'ASC');
        return $bannerCollection;
    }

    /**
     * @return int
     * Banner Collection Count
     */
    public function getBannerCount()
    {
        return count($this->getBannerCollection());
    }

    /**
     * @param $image
     * @return string
     */
    public function getBannerImageUrl($image)
    {
        return $this->bannersliderHelper->getBaseUrlMedia($image);
    }
}