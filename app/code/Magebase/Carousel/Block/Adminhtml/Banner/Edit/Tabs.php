<?php

namespace Magebase\Carousel\Block\Adminhtml\Banner\Edit;

/**
 * Class Tabs
 * @package Magebase\Carousel\Block\Adminhtml\Banner\Edit
 */
class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * construct.
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('banner_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Banner Information'));
    }
}