<?php
namespace Magebase\Carousel\Model\Banner\Source;

/**
 * Class IsActive
 * @package Magebase\Carousel\Model\Banner\Source
 */
class IsActive implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @var \Magebase\Carousel\Model\Banner
     */
    protected $banner;

    /**
     * @param \Magebase\Carousel\Model\Banner $banner
     */
    public function __construct(\Magebase\Carousel\Model\Banner $banner)
    {
        $this->banner = $banner;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options[] = ['label' => '', 'value' => ''];
        $availableOptions = $this->getOptionArray();
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }

    /**
     * @return array
     */
    public static function getOptionArray()
    {
        return [1 => __('Enabled'), 0 => __('Disabled')];
    }
}