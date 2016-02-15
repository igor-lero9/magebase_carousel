<?php
namespace Magebase\Carousel\Model\ResourceModel\Banner;

/**
 * Class BannerSliderCollection
 * @package Magebase\Carousel\Model\ResourceModel\Banner
 */
class BannerSliderCollection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var bool
     */
    protected $isJoinTable = false;

    /**
     * @param \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     * @param null $connection
     * @param \Magento\Framework\Model\ResourceModel\Db\AbstractDb|null $resource
     */
    public function __construct(
        \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        $connection = null,
        \Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource = null
    ) {
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $connection, $resource);
    }
    /**
     * _contruct
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Magebase\Carousel\Model\Banner', 'Magebase\Carousel\Model\ResourceModel\Banner');
    }

    /**
     * @param $sliderId
     * @return $this
     */
    public function getBannerResourceCollection($sliderId)
    {
        if(!$this->isJoinTable){
            $this->getSelect()->joinLeft(
                ['slider_banner' => $this->getTable('magebase_carousel_join')],
                'main_table.banner_id = slider_banner.banner_id'
            );
            $this->getSelect()->where(
                'slider_banner.slider_id = ?', $sliderId
            );

        }
        $this->isJoinTable = true;
        return $this;
    }
}
