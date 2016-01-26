<?php


namespace Magebase\Carousel\Model\ResourceModel\Slider;


use \Magebase\Carousel\Model\ResourceModel\AbstractCollection;
/**
 * Class Collection
 * @package Magebase\Carousel\Model\ResourceModel\Slider
 */
class Collection extends AbstractCollection
{
    /**
     * _contruct
     * @return void
     */

 	protected $_idFieldName = 'slider_id';

    /**
     * _construct
     */
    protected function _construct()
    {
        $this->_init('Magebase\Carousel\Model\Slider', 'Magebase\Carousel\Model\ResourceModel\Slider');
    }
}
