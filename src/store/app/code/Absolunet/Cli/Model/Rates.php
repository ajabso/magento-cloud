<?php
namespace Absolunet\Cli\Model;

use Absolunet\Cli\Api\Data\RatesInterface;

class Rates extends \Magento\Framework\Model\AbstractModel implements RatesInterface
{
    /** @var \Magento\TaxImportExport\Model\Rate\CsvImportHandlerFactory  */
    protected $csvImportHandler;

    /** @var \Magento\Framework\App\ResourceConnection */
    protected $resource;

    /**  @var \Magento\Framework\Filesystem\Io\File */
    protected $fileSystem;

    /**  @var \Symfony\Component\Console\Output\OutputInterface */
    protected $output;

    /**
     * Rates constructor.
     * @param \Magento\TaxImportExport\Model\Rate\CsvImportHandlerFactory $csvImportHandler
     * @param \Magento\Framework\App\ResourceConnection $resourceConnection
     * @param \Magento\Framework\Filesystem\Io\File $fileSystem
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\TaxImportExport\Model\Rate\CsvImportHandlerFactory $csvImportHandler,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Magento\Framework\Filesystem\Io\File $fileSystem,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->csvImportHandler = $csvImportHandler;
        $this->resource = $resourceConnection;
        $this->fileSystem = $fileSystem;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    public function setOutput($output)
    {
        $this->output = $output;

        return $this;
    }

    /**
     * Clear current tax rates
     *
     * @return $this
     */
    protected function clearRates()
    {
        $this->output->writeln('Clearing existing rates ...');

        $connection = $this->resource->getConnection();
        $connection->delete($connection->getTableName('tax_calculation_rate'));
        $connection->delete($connection->getTableName('tax_calculation_rate_title'));
        $connection->query('ALTER TABLE ' . $connection->getTableName('tax_calculation_rate') . ' AUTO_INCREMENT = 1');
        $connection->query('ALTER TABLE ' . $connection->getTableName('tax_calculation_rate_title') . ' AUTO_INCREMENT = 1');

        $this->output->writeln('Existing rates cleared');

        return $this;
    }

    /**
     * Install tax rates from csv file
     *
     * @param array $country
     * @param bool $keepSettings
     * @return bool|string
     */
    public function install($country, $keepSettings)
    {
        $file = [];
        $file['tmp_name'] = $this->fileSystem->dirname(__DIR__) . '/csv/rates/' . strtolower($country) . '.csv';

        if (!$keepSettings) {
            $this->clearRates();
        }

        try {
            $this->csvImportHandler->create()->importFromCsvFile($file);
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        return true;
    }
}
