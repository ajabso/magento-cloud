<?php
namespace Absolunet\Cli\Model;

use Absolunet\Cli\Api\Data\RulesInterface;
use Magento\Framework\Setup\SampleData\Context as SampleDataContext;

class Rules extends \Magento\Framework\Model\AbstractModel implements RulesInterface
{
    /** @var \Magento\Framework\File\Csv */
    protected $csvReader;

    /** @var \Magento\Tax\Api\TaxRateRepositoryInterfaceFactory */
    protected $taxRateRepository;

    /** @var \Magento\Tax\Api\TaxRuleRepositoryInterfaceFactory */
    protected $taxRuleRepository;

    /** @var \Magento\Tax\Api\Data\TaxRuleInterfaceFactory */
    protected $taxRule;

    /** @var \Magento\Framework\Api\SearchCriteriaBuilder */
    protected $searchCriteriaBuilder;

    /** @var \Magento\Framework\Api\FilterBuilder */
    protected $filterBuilder;

    /** @var \Magento\Framework\Api\Search\FilterGroupBuilder */
    protected $filterGroupBuilder;

    /** @var \Magento\Framework\App\ResourceConnection */
    protected $resource;

    /** @var \Magento\Framework\App\Config\ScopeConfigInterface */
    protected $scopeConfig;

    /** @var \Magento\Framework\App\Config\Storage\WriterInterface */
    protected $configWriter;

    /**  @var \Symfony\Component\Console\Output\OutputInterface */
    protected $output;

    /**  @var \Magento\Framework\Filesystem\Io\File */
    protected $fileSystem;

    /**
     * Rules constructor.
     * @param SampleDataContext $sampleDataContext
     * @param \Magento\Tax\Api\TaxRateRepositoryInterfaceFactory $taxRateRepository
     * @param \Magento\Tax\Api\TaxRuleRepositoryInterfaceFactory $taxRuleRepository
     * @param \Magento\Tax\Api\Data\TaxRuleInterfaceFactory $taxRule
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     * @param \Magento\Framework\Api\FilterBuilder $filterBuilder
     * @param \Magento\Framework\Api\Search\FilterGroupBuilder $filterGroupBuilder
     * @param \Magento\Framework\App\ResourceConnection $resourceConnection
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Framework\App\Config\Storage\WriterInterface $configWriter
     * @param \Magento\Framework\Filesystem\Io\File $fileSystem
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        SampleDataContext $sampleDataContext,
        \Magento\Tax\Api\TaxRateRepositoryInterfaceFactory $taxRateRepository,
        \Magento\Tax\Api\TaxRuleRepositoryInterfaceFactory $taxRuleRepository,
        \Magento\Tax\Api\Data\TaxRuleInterfaceFactory $taxRule,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Framework\Api\FilterBuilder $filterBuilder,
        \Magento\Framework\Api\Search\FilterGroupBuilder $filterGroupBuilder,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\App\Config\Storage\WriterInterface $configWriter,
        \Magento\Framework\Filesystem\Io\File $fileSystem,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->csvReader = $sampleDataContext->getCsvReader();
        $this->taxRateRepository = $taxRateRepository;
        $this->taxRuleRepository = $taxRuleRepository;
        $this->taxRule = $taxRule;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterBuilder = $filterBuilder;
        $this->filterGroupBuilder = $filterGroupBuilder;
        $this->resource = $resourceConnection;
        $this->scopeConfig = $scopeConfig;
        $this->configWriter = $configWriter;
        $this->fileSystem = $fileSystem;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    public function setOutput($output)
    {
        $this->output = $output;

        return $this;
    }

    /**
     * Clear current tax rules
     *
     * @return $this
     */
    protected function clearRules()
    {
        $this->output->writeln('Clearing existing rules ...');

        $connection = $this->resource->getConnection();
        $connection->delete($connection->getTableName('tax_calculation_rule'));
        $connection->query('ALTER TABLE ' . $connection->getTableName('tax_calculation_rule') . ' AUTO_INCREMENT = 1');
        $connection->query('ALTER TABLE ' . $connection->getTableName('tax_calculation') . ' AUTO_INCREMENT = 1');

        $this->output->writeln('Existing rules cleared');

        return $this;
    }

    /**
     * Set tax cart display as splitted
     *
     * @return $this
     */
    protected function updateCurrentTaxCartDisplayConfig()
    {
        $this->output->writeln('Updating cart tax display setting ...');

        $currentTaxCartDisplayConfig = $this->scopeConfig->getValue('tax/cart_display/full_summary');

        if ($currentTaxCartDisplayConfig) {
            $connection = $this->resource->getConnection();
            $connection->delete(
                $connection->getTableName('core_config_data'),
                ['path = ?' => 'tax/cart_display/full_summary']
            );
        }

        $this->configWriter->save('tax/cart_display/full_summary', 1);

        $this->output->writeln('Cart tax display setting updated');

        return $this;
    }

    /**
     * Install tax rules from csv file
     *
     * @param array $country
     * @param bool $keepSettings
     * @return bool|string
     */
    public function install($country, $keepSettings)
    {
        if (!$keepSettings) {
            $this->clearRules();
        }

        $this->updateCurrentTaxCartDisplayConfig();

        $rows = $this->csvReader->getData($this->fileSystem->dirname(__DIR__) . '/csv/rules/' . strtolower($country) . '.csv');
        array_shift($rows);

        foreach ($rows as $row) {
            try {
                /** @var \Magento\Tax\Model\Calculation\Rule $rule */
                $rule = $this->taxRule->create();
                $rule->setCode($row[0])
                    ->setPosition($row[1])
                    ->setPriority($row[2])
                    ->setCalculateSubtotal($row[3])
                    ->setCustomerTaxClassIds(explode(',', $row[4]))
                    ->setProductTaxClassIds(explode(',', $row[5]))
                    ->setTaxRateIds($this->getTaxRateIds($row[6]));

                $this->taxRuleRepository->create()->save($rule);
            } catch (\Exception $e) {
                $this->output->writeln($row[0] . ' ' . $e->getMessage());
            }
        }

        return true;
    }

    /**
     * Get tax rate ids from their code
     *
     * @param string $taxRateCodes
     * @return array
     */
    protected function getTaxRateIds($taxRateCodes)
    {
        $taxRateIds = [];
        $rateRepository = $this->taxRateRepository->create();
        $taxRateCodes = explode(',', $taxRateCodes);

        foreach ($taxRateCodes as $taxRateCode) {
            $this->filterGroupBuilder->addFilter(
                $this->filterBuilder->setField('code')->setConditionType('eq')->setValue($taxRateCode)->create()
            );
        }

        $filterGroup = $this->filterGroupBuilder->create();

        /** @var \Magento\Framework\Api\SearchCriteriaBuilder $searchCriterias */
        $searchCriterias = $this->searchCriteriaBuilder
            ->setCurrentPage(1)
            ->setPageSize(false)
            ->setFilterGroups([$filterGroup])
            ->create();
        $rates = $rateRepository->getList($searchCriterias)->getItems();

        foreach ($rates as $rate) {
            $taxRateIds[] = $rate['tax_calculation_rate_id'];
        }

        return $taxRateIds;
    }
}
