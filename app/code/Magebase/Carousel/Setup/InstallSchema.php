<?php

namespace Magebase\Carousel\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

/**
 * Class InstallSchema
 * @package Magebase\Carousel\Setup
 */
class InstallSchema implements InstallSchemaInterface{

    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context){
        $installer = $setup;

        $installer->startSetup();


        /**
         * Drop tables if exist
         */

        $installer->getConnection()->dropTable($installer->getTable('magebase_carousel_slider'));
        $installer->getConnection()->dropTable($installer->getTable('magebase_carousel_banner'));
        $installer->getConnection()->dropTable($installer->getTable('magebase_carousel_join'));

        /**
         * Create Magebase_Carousel_Slider table
         */
        $table = $installer->getConnection()
            ->newTable($installer->getTable('magebase_carousel_slider'))
            ->addColumn('slider_id', Table::TYPE_SMALLINT,null,['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true], 'Slider ID')
            ->addColumn('title', Table::TYPE_TEXT, 255, ['nullable' => false, 'default' => ''], 'Slider Title')
            ->addColumn('slider_status', Table::TYPE_SMALLINT, null, ['nullable' => false, 'default' => '1'], 'Slider Status')
            ->addColumn('banner_number', Table::TYPE_INTEGER, 40, ['nullable' => true], 'Number of items')
            ->addColumn('loop', Table::TYPE_SMALLINT, null, ['nullable' => false, 'default' => '1'], 'Inifnity loop')
            ->addColumn('mouse_drag', Table::TYPE_SMALLINT, null, ['nullable' => false, 'default' => '1'], 'Mouse drag enabled')
            ->addColumn('auto_width', Table::TYPE_SMALLINT, null, ['nullable' => false, 'default' => '1'], 'Set Automatic Width')
            ->addColumn('start_position', Table::TYPE_INTEGER, 40, ['nullable' => true], 'Set banner start position')
            ->addColumn('nav', Table::TYPE_SMALLINT, null, ['nullable' => false, 'default' => '1'], 'Enable Navigation')
            ->addColumn('dots', Table::TYPE_SMALLINT, null, ['nullable' => false, 'default' => '1'], 'Enable Dots')
            ->addColumn('auto_play', Table::TYPE_SMALLINT, null, ['nullable' => false, 'default' => '1'], 'Enable Autoplay')
            ->addColumn('autoplay_timeout', Table::TYPE_INTEGER, 10000, ['nullable' => true], 'Autoplay Timeout')
            ->addColumn('autoplay_speed', Table::TYPE_INTEGER, 10000, ['nullable' => true], 'Autoplay Speed')
            ->addColumn('animate_in', Table::TYPE_TEXT, 255, ['nullable' => true], 'AnimateIn')
            ->addColumn('animate_out', Table::TYPE_TEXT, 255, ['nullable' => true], 'AnimateOut')
            ->addColumn('include_block', Table::TYPE_SMALLINT, null, ['nullable' => true], 'Include Static Blocks')
            ->addColumn('first_block', Table::TYPE_SMALLINT, null, ['nullable' => true], 'CMS Block 1')
            ->addColumn('second_block', Table::TYPE_SMALLINT, null, ['nullable' => true], 'CMS Block 2')
            ->addColumn('responsive_rules', Table::TYPE_TEXT, 255, ['nullable' => true], 'Set Responsive Rules')
            ->addIndex(
                $installer->getIdxName('magebase_carousel_slider', ['slider_id']),
                ['slider_id']
            )->addIndex(
                $installer->getIdxName('magebase_carousel_slider', ['start_position']),
                ['start_position']
            )->addIndex(
                $installer->getIdxName('magebase_carousel_slider', ['slider_status']),
                ['slider_status']
            );

        $installer->getConnection()->createTable($table);


        $table = $installer->getConnection()
            ->newTable($installer->getTable('magebase_carousel_banner'))
            ->addColumn('banner_id', Table::TYPE_SMALLINT,null,['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true], 'Banner ID')
            ->addColumn('name', Table::TYPE_TEXT, 255, ['nullable' => true, 'default' => ''], 'Banner Name')
            ->addColumn('type', Table::TYPE_TEXT, 255, ['nullable' => false, 'default' => '0'], 'Banner Type')
            ->addColumn('status', Table::TYPE_SMALLINT, null, ['nullable' => false, 'default' => '1'], 'Banner Status')
            ->addColumn('order_banner', Table::TYPE_INTEGER, 40, ['nullable' => true], 'Banner order')
            ->addColumn('url', Table::TYPE_TEXT, 255, ['nullable' => false, 'default' => ''], 'Banner URL')
            ->addColumn('target', Table::TYPE_SMALLINT, null, ['nullable' => true, 'default' => '0'], 'URL target')
            ->addColumn('image', Table::TYPE_TEXT, 255, ['nullable' => true, 'default' => ''], 'Banner Image')
            ->addColumn('image_alt', Table::TYPE_TEXT, 255, ['nullable' => true, 'default' => ''], 'Image Alt text')
            ->addColumn('video', Table::TYPE_TEXT, 255, ['nullable' => true, 'default' => ''], 'Banner Video')
            ->addColumn('video_content', Table::TYPE_TEXT, 255, ['nullable' => true, 'default' => ''], 'Banner Video Text')
            ->addColumn('start_time', Table::TYPE_DATETIME, null, ['nullable' => true], 'Banner starting time')
            ->addColumn('end_time', Table::TYPE_DATETIME, null, ['nullable' => true], 'Banner ending time')
            ->addIndex(
                $installer->getIdxName('magebase_carousel_banner', ['banner_id']),
                ['banner_id']
            )->addIndex(
                $installer->getIdxName('magebase_carousel_slider', ['status']),
                ['status']
            )->addIndex(
                $installer->getIdxName('magebase_carousel_slider', ['start_time']),
                ['start_time']
            )->addIndex(
                $installer->getIdxName('magebase_carousel_slider', ['end_time']),
                ['end_time']
            );

        $installer->getConnection()->createTable($table);

        /*
         * Create table magebase_carousel_joint
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('magebase_carousel_join')
        )->addColumn('slider_id', Table::TYPE_SMALLINT, null, ['unsigned' => true, 'nullable' => true, 'default' => '0'], 'Slider ID'
        )->addColumn('banner_id', Table::TYPE_SMALLINT, null, ['unsigned' => true, 'nullable' => true, 'default' => '0'], 'Banner ID'
        )->addIndex(
            $installer->getIdxName(
                'magebase_carousel_join',
                ['slider_id', 'banner_id'],
                \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
            ),
            ['slider_id', 'banner_id'],
            ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
        )->addIndex(
            $installer->getIdxName('magebase_carousel_join', ['slider_id']),
            ['slider_id']
        )->addIndex(
            $installer->getIdxName('magebase_carousel_join', ['banner_id']),
            ['banner_id']
        )->addForeignKey(
            $installer->getFkName(
                'magebase_carousel_join',
                'slider_id',
                'magebase_carousel_slider',
                'slider_id'
            ),
            'slider_id',
            $installer->getTable('magebase_carousel_slider'),
            'slider_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->addForeignKey(
            $installer->getFkName(
                'magebase_carousel_join',
                'banner_id',
                'magebase_carousel_banner',
                'banner_id'
            ),
            'banner_id',
            $installer->getTable('magebase_carousel_banner'),
            'banner_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        );
        $installer->getConnection()->createTable($table);


        /**
         * Create table 'slider_store_join'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('slider_store_join')
        )->addColumn(
            'slider_id',
            Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'nullable' => false, 'primary' => true],
            'Slider ID'
        )->addColumn(
            'store_id',
            Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'nullable' => false, 'primary' => true],
            'Store ID'
        )->addIndex(
            $installer->getIdxName('slider_store_join', ['store_id']),
            ['store_id']
        )->addForeignKey(
            $installer->getFkName('slider_store_join', 'slider_id', 'magebase_carousel_slider', 'slider_id'),
            'slider_id',
            $installer->getTable('magebase_carousel_slider'),
            'slider_id',
            Table::ACTION_CASCADE
        )->addForeignKey(
            $installer->getFkName('slider_store_join', 'store_id', 'store', 'store_id'),
            'store_id',
            $installer->getTable('store'),
            'store_id',
            Table::ACTION_CASCADE
        )->setComment(
            'Magebase Slider To Store Linkage Table'
        );
        $installer->getConnection()->createTable($table);

        $installer->endSetup();
    }
}