<?php
namespace Magebase\Carousel\Model\Slider\Source;

/**
 * Class Status
 * @package Magebase\Carousel\Model\Slider\Source
 */
class Status implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @var \Magebase\Carousel\Model\Slider
     */
    protected $slider;

    /**
     * @param \Magebase\Carousel\Model\Slider $slider
     */
    public function __construct(\Magebase\Carousel\Model\Slider $slider)
    {
        $this->slider = $slider;
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