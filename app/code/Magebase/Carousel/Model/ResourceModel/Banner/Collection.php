<?php


namespace Magebase\Carousel\Model\ResourceModel\Banner;

use \Magebase\Carousel\Model\ResourceModel\AbstractCollection;
/**
 * Class Collection
 * @package Magebase\Carousel\Model\ResourceModel\Banner
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'banner_id';

    /**
     * _contruct
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Magebase\Carousel\Model\Banner', 'Magebase\Carousel\Model\ResourceModel\Banner');
    }

    public function getBannerRelationCollection($sliderId){
        $collection = $this->joinLeftCarouselRelationTable('magebase_carousel_join', 'banner_id', 'slider_id', $sliderId);
        return $collection;
    }

}
