<?php
namespace Absolunet\Cli\Model;

use Absolunet\Cli\Api\Data\TestDatasInterface;
use Magento\Framework\App\Filesystem\DirectoryList;

class TestDatas extends \Magento\Framework\Model\AbstractModel implements TestDatasInterface
{
    /** @var \Magento\Framework\App\ResourceConnection */
    protected $resource;

    /** @var \Magento\Store\Api\StoreRepositoryInterface */
    protected $storeModel;

    /**  @var \Magento\Framework\App\Filesystem\DirectoryList */
    protected $directoryList;

    /**  @var \Magento\Framework\Filesystem\Io\File */
    protected $fileSystem;

    /**
     * TestDatas constructor.
     * @param \Magento\Framework\App\ResourceConnection $resourceConnection
     * @param \Magento\Store\Api\StoreRepositoryInterfaceFactory $storeModel
     * @param \Magento\Framework\App\Filesystem\DirectoryList $directoryList
     * @param \Magento\Framework\Filesystem\Io\File $fileSystem
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Magento\Store\Api\StoreRepositoryInterfaceFactory $storeModel,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \Magento\Framework\Filesystem\Io\File $fileSystem,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->resource = $resourceConnection;
        $this->storeModel = $storeModel;
        $this->directoryList = $directoryList;
        $this->fileSystem = $fileSystem;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Get all tables related with orders
     * DELETE ON CASCADE included
     *
     * @return array
     */
    protected function getOrderTables()
    {
        return [
            'sales_order',
            'sales_order_grid',
            'magento_sales_order_grid_archive',
            'sales_bestsellers_aggregated_daily',
            'sales_bestsellers_aggregated_monthly',
            'sales_bestsellers_aggregated_yearly',
            'sales_order_aggregated_created',
            'sales_order_aggregated_updated',
            'sales_order_tax',
            'sales_order_tax_item',
            'sales_refunded_aggregated',
            'sales_refunded_aggregated_order',
        ];
    }

    /**
     * Get all tables related with products
     * DELETE ON CASCADE included
     *
     * @return array
     */
    protected function getProductTables()
    {
        return [
            'catalog_product_entity',
            'url_rewrite',
            'catalog_category_product',
            'catalog_category_product_index',
            'catalog_url_rewrite_product_category'
        ];
    }

    /**
     * Get all tables related with categories
     * DELETE ON CASCADE included
     *
     * @return array
     */
    protected function getCategoryTables()
    {
        return [
            'catalog_category_entity',
            'url_rewrite',
            'catalog_category_product',
            'catalog_category_product_index',
            'catalog_url_rewrite_product_category'
        ];
    }

    /**
     * Get all tables related with invoice
     * DELETE ON CASCADE included
     *
     * @return array
     */
    protected function getInvoiceTables()
    {
        return [
            'sales_invoice',
            'sales_invoiced_aggregated',
            'sales_invoiced_aggregated_order',
            'sales_invoice_grid',
            'magento_sales_invoice_grid_archive'
        ];
    }

    /**
     * Get all tables related with shipping
     * DELETE ON CASCADE included
     *
     * @return array
     */
    protected function getShippingTables()
    {
        return [
            'sales_shipment',
            'sales_shipping_aggregated',
            'sales_shipping_aggregated_order',
            'sales_shipment_grid',
            'magento_sales_shipment_grid_archive'
        ];
    }

    /**
     * Get all tables related with customers
     * DELETE ON CASCADE included
     *
     * @return array
     */
    protected function getCustomerTables()
    {
        return [
            'customer_entity',
            'customer_grid_flat',
            'magento_customerbalance',
            'customer_log',
            'customer_visitor',
            'wishlist',
            'magento_reward',
            'magento_reward_rate',
            'magento_reward_salesrule',
            'sendfriend_log',
            'gift_message'
        ];
    }

    /**
     * Get all tables related with quote
     * DELETE ON CASCADE included
     *
     * @return array
     */
    protected function getQuotesTables()
    {
        return [
            'quote'
        ];
    }

    /**
     * Get all tables related with credit memo
     * DELETE ON CASCADE included
     *
     * @return array
     */
    protected function getCreditMemoTables()
    {
        return [
            'sales_creditmemo',
            'sales_creditmemo_grid',
            'magento_sales_creditmemo_grid_archive',
        ];
    }

    /**
     * Get all tables related with rma
     * DELETE ON CASCADE included
     *
     * @return array
     */
    protected function getRmaTables()
    {
        return [
            'magento_rma',
            'magento_rma_grid',
        ];
    }

    /**
     * Get all tables related with gift cards
     * DELETE ON CASCADE included
     *
     * @return array
     */
    protected function getGiftCardTables()
    {
        return [
            'magento_giftcard_amount',
            'magento_giftcardaccount',
            'magento_giftcardaccount_history',
            'magento_giftcardaccount_pool',
        ];
    }

    /**
     * Get all tables related with gift registry
     * DELETE ON CASCADE included
     *
     * @return array
     */
    protected function getGiftRegistryTables()
    {
        return [
            'magento_giftregistry_entity'
        ];
    }

    /**
     * Get all tables related with reviews
     * DELETE ON CASCADE included
     *
     * @return array
     */
    protected function getReviewTables()
    {
        return [
            'review',
            'review_entity_summary'
        ];
    }

    /**
     * Reset increment id for sequence tables
     *
     * @param string $code
     * @return array
     */
    protected function resetSequence($code)
    {
        $connection = $this->resource->getConnection();
        $addStoreId = true;

        switch ($code) {
            case 'all':
            case 'order':
                $sequenceTables = ['invoice', 'order', 'shipment', 'creditmemo', 'rma_item'];
                break;
            case 'invoice':
                $sequenceTables = ['invoice'];
                break;
            case 'shipping':
                $sequenceTables = ['shipment'];
                break;
            case 'creditmemo':
                $sequenceTables = ['creditmemo'];
                break;
            case 'product':
                $sequenceTables = ['product'];
                $addStoreId = false;
                break;
            case 'category':
                $sequenceTables = ['catalog_category'];
                $addStoreId = false;
                break;
            case 'rma':
                $sequenceTables = ['rma_item'];
                break;
            default:
                $sequenceTables = [];
        }

        foreach ($this->getStoreIds() as $storeId) {
            $where = [];
            $autoIncrement = 1;

            if ($code == 'category') {
                $where = 'sequence_value > 2';
                $autoIncrement = 3;
            }

            foreach ($sequenceTables as $sequenceTable) {
                if ($addStoreId) {
                    $tableName = $connection->getTableName('sequence_' . $sequenceTable . '_' . $storeId);
                } else {
                    $tableName = $connection->getTableName('sequence_' . $sequenceTable);
                }

                $connection->delete($tableName, $where);
                $connection->query('ALTER TABLE ' . $tableName . ' AUTO_INCREMENT = ' . $autoIncrement);
            }
        }
    }

    /**
     * Get all store ids
     *
     * @return array
     */
    protected function getStoreIds()
    {
        $storeIds = [];

        $stores = $this->storeModel->create()->getList();
        foreach ($stores as $store) {
            $storeIds[] = $store->getId();
        }

        return $storeIds;
    }

    /**
     * Delete datas in tables by code
     *
     * @param string $code
     * @return bool|string
     */
    public function deleteByCode($code)
    {
        $connection = $this->resource->getConnection();

        switch ($code) {
            case 'all':
                $tables = array_merge(
                    $this->getOrderTables(),
                    $this->getInvoiceTables(),
                    $this->getShippingTables(),
                    $this->getCustomerTables(),
                    $this->getQuotesTables(),
                    $this->getCreditMemoTables(),
                    $this->getRmaTables(),
                    $this->getGiftCardTables(),
                    $this->getGiftRegistryTables(),
                    $this->getProductTables(),
                    $this->getCategoryTables()
                );
                break;
            case 'order':
                $tables = array_merge(
                    $this->getOrderTables(),
                    $this->getInvoiceTables(),
                    $this->getShippingTables(),
                    $this->getCreditMemoTables(),
                    $this->getRmaTables()
                );
                break;
            case 'invoice':
                $tables = $this->getInvoiceTables();
                break;
            case 'shipping':
                $tables = $this->getShippingTables();
                break;
            case 'creditmemo':
                $tables = $this->getCreditMemoTables();
                break;
            case 'rma':
                $tables = $this->getRmaTables();
                break;
            case 'quote':
                $tables = $this->getQuotesTables();
                break;
            case 'gift_card':
                $tables = $this->getGiftCardTables();
                break;
            case 'gift_registry':
                $tables = $this->getGiftRegistryTables();
                break;
            case 'product':
                $tables = array_merge(
                    $this->getProductTables(),
                    $this->getReviewTables()
                );
                $this->deleteSampleMedias();
                break;
            case 'category':
                $tables = $this->getCategoryTables();
                break;
            case 'customer':
                $tables = $this->getCustomerTables();
                break;
            default:
                $tables = [];
        }

        foreach ($tables as $table) {
            $where = [];
            $autoIncrement = 1;

            if ($code == 'category' && $table == 'catalog_category_entity') {
                $where = 'entity_id > 2';
                $autoIncrement = '3';
            }

            try {
                $tableName = $connection->getTableName($table);
                $connection->delete($tableName, $where);
                $connection->query('ALTER TABLE ' . $tableName . ' AUTO_INCREMENT = ' . $autoIncrement);
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }

        $this->resetSequence($code);

        return true;
    }

    /**
     * Delete products and categories media files
     *
     * @return $this
     */
    protected function deleteSampleMedias()
    {
        $mediaDir = $this->directoryList->getPath(DirectoryList::MEDIA);
        $this->fileSystem->rmdir($mediaDir . '/catalog', true);

        return $this;
    }
}
