<?php

namespace Magebase\Carousel\Model\ResourceModel;


use Magento\Cms\Api\Data\BlockInterface;
use Magento\Framework\Model\ResourceModel\Db\Context;

/**
 * Class Banner
 * @package Magebase\Carousel\Model\ResourceModel
 */
class Banner extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * @param Context $context
     * @param null $connectionName
     */
    public function __construct(
        Context $context,
        $connectionName = null
    )
    {
        parent::__construct($context, $connectionName);
    }

    /**
     * construct
     * @return void
     */
    protected function _construct()
    {
        $this->_init('magebase_carousel_banner', 'banner_id');
    }

    /**
     * @param $id
     * @return array
     */
    public function lookupBannerIds($id)
    {
        $connection = $this->getConnection();

        $select = $connection->select()
            ->join(
                ['slider_banner' => $this->getTable('magebase_carousel_join')],
                'main_table.banner_id = slider_banner.banner_id'
            )
            ->where('slider_banner.slider_id = ?', $id);

        $bannerIds = [];

        $bannerIdArray = $connection->fetchAll($select, ['slider_id' => (int)$id]);

        foreach ($bannerIdArray as $banner) {
            $bannerIds[] = $banner['banner_id'];
        }

        return $bannerIds;
    }
}