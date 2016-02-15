<?php
namespace Magebase\Carousel\Block\Adminhtml\Slider\Edit\Tab;

//use Magebase\Carousel\Model\Status;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Framework\Controller\ResultFactory;

/**
 *
 * Class Banners
 * @package Magebase\Carousel\Block\Adminhtml\Slider\Edit\Tab
 *
 */
class Banners extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var bannerCollection
     */
    protected $bannerCollection;

    /**
     * @var sliderFactory
     */
    protected $sliderFactory;
    /**
     * @var bool
     */
    protected $_isLoadBanners = FALSE;

    /**
     * @var array
     */
    protected $_bannerIds = [];
    /**
     * @var \Magento\Framework\Json\EncoderInterface
     */
    protected $_jsonEncoder;

    /**
     *
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     * @param \Magebase\Carousel\Model\ResourceModel\Banner\Collection $bannerCollection
     * @param array $data
     *
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magebase\Carousel\Model\ResourceModel\Banner\Collection $bannerCollection,
        array $data = []
    )
    {
        $this->_jsonEncoder = $jsonEncoder;
        $this->_bannerCollection = $bannerCollection;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * _construct
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('bannerGrid');
        $this->setDefaultSort('banner_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
        if ($this->getRequest()->getParam('slider_id')) {
            $this->setDefaultFilter(array('in_banner' => 1));
        }
    }

    /**
     * add Column Filter To Collection
     */

    protected function _addColumnFilterToCollection($column)
    {
        if ($column->getId() == 'in_banner') {
            $bannerIds = $this->getSelectedSliderBanners();
            if (empty($bannerIds)) {
                $bannerIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('banner_id', array('in' => $bannerIds));
            } else {
                if ($bannerIds) {
                    $this->getCollection()->addFieldToFilter('banner_id', array('nin' => $bannerIds));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }

        return $this;
    }

    /**
     * prepare collection
     */
    protected function _prepareCollection()
    {
        /** @var \Magestore\Bannerslider\Model\ResourceModel\Banner\Collection $collection */
        $collection = $this->_bannerCollection;

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * @return $this
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'in_banner',
            [
                'header_css_class' => 'a-center',
                'type' => 'checkbox',
                'name' => 'in_banner',
                'name' => 'banner_id',
                'align' => 'center',
                'index' => 'banner_id',
                'values' => $this->getSelectedSliderBanners(),
            ]
        );
        $this->addColumn(
            'banner_id',
            [
                'header' => __('Banner ID'),
                'type' => 'number',
                'index' => 'banner_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id',
            ]
        );
        $this->addColumn(
            'name',
            [
                'header' => __('Name'),
                'index' => 'name',
                'class' => 'xxx',
                'width' => '50px',
            ]
        );
        $this->addColumn(
            'image',
            [
                'header' => __('Image'),
                'filter' => false,
                'class' => 'xxx',
                'width' => '50px',
                'index' => 'image',
                'renderer' => 'Magebase\Carousel\Block\Adminhtml\Banner\Helper\Renderer\Image',
            ]
        );
        $this->addColumn(
            'start_time',
            [
                'header' => __('Starting time'),
                'type' => 'datetime',
                'index' => 'start_time',
                'class' => 'xxx',
                'width' => '50px',
                'timezone' => true,
            ]
        );

        $this->addColumn(
            'end_time',
            [
                'header' => __('Ending time'),
                'type' => 'datetime',
                'index' => 'end_time',
                'class' => 'xxx',
                'width' => '50px',
                'timezone' => true,
            ]
        );

        $this->addColumn(
            'status',
            [
                'header' => __('Status'),
                'index' => 'status',
                'type' => 'options',
                'filter_index' => 'main_table.status',
                'options' => $this->getAvailableStatuses(),
            ]
        );
        $this->addColumn(
            'order_banner',
            [
                'header' => __('Order'),
                'name' => 'order_banner',
                'index' => 'order_banner',
                'class' => 'xxx',
                'width' => '50px',
                'editable' => true,
            ]
        );
        return parent::_prepareColumns();
    }

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/bannersgrid', ['_current' => true]);
    }

    /**
     * get row url
     * @param  object $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return '';
    }

    /**
     * @return array
     * Get Assosiated Banner Ids
     */
    public function getSelectedSliderBanners()
    {
        $sliderId = $this->getRequest()->getParam('slider_id');
        $bannerIds = [];
        if (!isset($sliderId)) {
            return [];
        }
        $bannerCollection = $this->_bannerCollection->getBannerRelationCollection($sliderId);
        foreach ($bannerCollection as $banner) {
            $bannerIds[] = $banner->getId();
        }
        return $bannerIds;
    }

    /**
     * Prepare label for tab.
     *
     * @return string
     */
    public function getTabLabel()
    {
        return __('Banners');
    }

    /**
     * Prepare title for tab.
     *
     * @return string
     */
    public function getTabTitle()
    {
        return __('Banners');
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
        return true;
    }

    public static function getAvailableStatuses()
    {
        return [
            0 => __('Disabled'), 1 => __('Enabled')
        ];
    }
}
