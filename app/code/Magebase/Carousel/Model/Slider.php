<?php
namespace Magebase\Carousel\Model;
/**
 * Class Slider
 * @package Magebase\Carousel\Model
 */
class Slider extends \Magento\Framework\Model\AbstractModel
{
    /**
     * CMS page cache tag
     */
    const CACHE_TAG = 'carousel_slider';

    /**
     * @var string
     */
    protected $_cacheTag = self::CACHE_TAG;

    /**
     *
     */
    protected function _construct()
    {
        $this->_init('Magebase\Carousel\Model\ResourceModel\Slider');
    }

}