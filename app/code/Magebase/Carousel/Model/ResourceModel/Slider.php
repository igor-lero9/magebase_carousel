<?php

namespace Magebase\Carousel\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Slider
 * @package Magebase\Carousel\Model\ResourceModel
 */
class Slider extends AbstractDb
{
    /**
     * Store model
     *
     * @var null|\Magento\Store\Model\Store
     */
    protected $_store = null;
    /**
     * @var \Magento\Backend\Helper\Js
     */
    protected $_jsHelper;
    /**
     * @var Page
     */
    protected $resourceBanner;

    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param \Magento\Backend\Helper\Js $jsHelper
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param Banner $resourceBanner
     * @param null $connectionName
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Magento\Backend\Helper\Js $jsHelper,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magebase\Carousel\Model\ResourceModel\Banner $resourceBanner,
        $connectionName = null
    )
    {
        parent::__construct($context, $connectionName);
        $this->_jsHelper = $jsHelper;
        $this->_storeManager = $storeManager;
        $this->resourceBanner = $resourceBanner;
    }

    /**
     * construct
     * @return void
     */
    protected function _construct()
    {
        $this->_init('magebase_carousel_slider', 'slider_id');
    }


    /**
     * Process page data before deleting
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     */
    protected function _beforeDelete(\Magento\Framework\Model\AbstractModel $object)
    {
        $condition = ['slider_id = ?' => (int)$object->getId()];

        $this->getConnection()->delete($this->getTable('magebase_carousel_join'), $condition);
        $this->getConnection()->delete($this->getTable('slider_store_join'), $condition);
        return parent::_beforeDelete($object);
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     */
    protected function _afterSave(\Magento\Framework\Model\AbstractModel $object)
    {
        if (isset($object['bannerIds'])) {

            $oldBanners = $this->resourceBanner->lookupBannerIds($object->getId());
            $newBanners = (array)$object->getItemsId();

            $table = $this->getTable('magebase_carousel_join');
            $insert = array_diff($newBanners, $oldBanners);
            $delete = array_diff($oldBanners, $newBanners);
            if ($delete) {
                $where = ['slider_id = ?' => (int)$object->getId(), 'banner_id IN (?)' => $delete];
                $this->getConnection()->delete($table, $where);
            }
            if ($insert) {
                $data = [];
                foreach ($insert as $bannerId) {
                    $data[] = ['slider_id' => (int)$object->getId(), 'banner_id' => (int)$bannerId];
                }
                $this->getConnection()->insertMultiple($table, $data);
            }
            return parent::_afterSave($object);

        }
        if (isset($object['stores'])) {
            $oldStores = $this->lookupStoreIds($object->getId());
            $newStores = (array)$object->getStores();
            if (empty($newStores)) {
                $newStores = (array)$object->getStoreId();
            }
            $table = $this->getTable('slider_store_join');
            $storeInsert = array_diff($newStores, $oldStores);
            $storeDelete = array_diff($oldStores, $newStores);

            if ($storeDelete) {
                $where = ['slider_id = ?' => (int)$object->getId(), 'store_id IN (?)' => $storeDelete];

                $this->getConnection()->delete($table, $where);
            }

            if ($storeInsert) {
                $data = [];

                foreach ($storeInsert as $storeId) {
                    $data[] = ['slider_id' => (int)$object->getId(), 'store_id' => (int)$storeId];
                }

                $this->getConnection()->insertMultiple($table, $data);
            }
        }

    }

    /**
     * Perform operations after object load
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     */
    protected function _afterLoad(\Magento\Framework\Model\AbstractModel $object)
    {
        if ($object->getId()) {
            $stores = $this->lookupStoreIds($object->getId());

            $object->setData('store_id', $stores);
        }

        return parent::_afterLoad($object);
    }

    /**
     * Get store ids to which specified item is assigned
     *
     * @param int $pageId
     * @return array
     */
    public function lookupStoreIds($slider_id)
    {
        $connection = $this->getConnection();

        $select = $connection->select()->from(
            $this->getTable('slider_store_join'),
            'store_id'
        )->where(
            'slider_id = ?',
            (int)$slider_id
        );

        return $connection->fetchCol($select);
    }

    /**
     * Set store model
     *
     * @param \Magento\Store\Model\Store $store
     * @return $this
     */
    public function setStore($store)
    {
        $this->_store = $store;
        return $this;
    }

    /**
     * Retrieve store model
     *
     * @return \Magento\Store\Model\Store
     */
    public function getStore()
    {
        return $this->_storeManager->getStore($this->_store);
    }
}
