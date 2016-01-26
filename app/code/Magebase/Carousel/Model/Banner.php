<?php

namespace Magebase\Carousel\Model;

/**
 * Class Banner
 * @package Magebase\Carousel\Model
 */
class Banner extends \Magento\Framework\Model\AbstractModel
{
    /**
     * BASE_MEDIA_PATH
     */
    const BASE_MEDIA_PATH = 'magebase/carousel/images';
    /**
     * BANNER_TARGET
     */
    const BANNER_TARGET_SELF = 0;
    const BANNER_TARGET_PARENT = 1;
    const BANNER_TARGET_BLANK = 2;

    /**
     * @var
     */
    protected $_bannerFactory;

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param ResourceModel\Banner $resource
     * @param ResourceModel\Banner\Collection $resourceCollection
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magebase\Carousel\Model\ResourceModel\Banner  $resource,
        \Magebase\Carousel\Model\ResourceModel\Banner\Collection  $resourceCollection
    ){
        parent::__construct($context, $registry, $resource, $resourceCollection);
    }

    /**
     * @return string
     */
    public function getTargetValue()
    {
        switch ($this->getTarget()) {
            case self::BANNER_TARGET_SELF:
                return '_self';
            case self::BANNER_TARGET_PARENT:
                return '_parent';

            default:
                return '_blank';
        }
    }
}