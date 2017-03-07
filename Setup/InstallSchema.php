<?php
/**
 * Workshop_Faq extension
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 *
 * @category  Workshop
 * @package   Workshop_Faq
 * @copyright Copyright (c) 2017
 * @license   http://opensource.org/licenses/mit-license.php MIT License
 */
namespace Workshop\Faq\Setup;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * install tables
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        if (!$installer->tableExists('workshop_faq_faq')) {
            $table = $installer->getConnection()->newTable($installer->getTable('workshop_faq_faq'))
                ->addColumn(
                    'faq_id',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'identity' => true,
                        'nullable' => false,
                        'primary'  => true,
                        'unsigned' => true,
                    ],
                    'FAQ ID'
                )
                ->addColumn(
                    'question',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable => false'],
                    'FAQ Question'
                )
                ->addColumn(
                    'answer',
                    Table::TYPE_TEXT,
                    '64k',
                    ['nullable => false'],
                    'FAQ Answer'
                )
                ->addColumn(
                    'sort_order',
                    Table::TYPE_INTEGER,
                    null,
                    [],
                    'FAQ Sort Order'
                )
                ->addColumn(
                    'is_active',
                    Table::TYPE_INTEGER,
                    1,
                    ['nullable => false'],
                    'FAQ Is Active'
                )

                ->addColumn(
                    'created_at',
                    Table::TYPE_TIMESTAMP,
                    null,
                    [
                        'nullable' => false,
                        'default' => Table::TIMESTAMP_INIT
                    ],
                    'FAQ Created At'
                )
                ->addColumn(
                    'updated_at',
                    Table::TYPE_TIMESTAMP,
                    null,
                    [
                        'nullable' => false,
                        'default' => Table::TIMESTAMP_INIT_UPDATE
                    ],
                    'FAQ Updated At'
                )
                ->setComment('FAQ Table');
            $installer->getConnection()->createTable($table);

            $installer->getConnection()->addIndex(
                $installer->getTable('workshop_faq_faq'),
                $setup->getIdxName(
                    $installer->getTable('workshop_faq_faq'),
                    ['question','answer'],
                    AdapterInterface::INDEX_TYPE_FULLTEXT
                ),
                ['question','answer'],
                AdapterInterface::INDEX_TYPE_FULLTEXT
            );
        }
        $installer->endSetup();
    }
}
