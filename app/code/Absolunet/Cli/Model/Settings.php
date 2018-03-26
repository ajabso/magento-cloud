<?php
namespace Absolunet\Cli\Model;

use Absolunet\Cli\Api\Data\SettingsInterface;
use Magento\Framework\Setup\SampleData\Context as SampleDataContext;

class Settings extends \Magento\Framework\Model\AbstractModel implements SettingsInterface
{
    /** @var \Magento\Framework\File\Csv */
    protected $csvReader;

    /** @var \Magento\Framework\App\ResourceConnection */
    protected $resource;

    /** @var \Magento\Framework\App\Config\Storage\WriterInterface */
    protected $configWriter;

    /**  @var \Symfony\Component\Console\Output\OutputInterface */
    protected $output;

    /**  @var \Magento\Framework\Filesystem\Io\File */
    protected $fileSystem;

    /**
     * Settings constructor.
     * @param SampleDataContext $sampleDataContext
     * @param \Magento\Framework\App\ResourceConnection $resourceConnection
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
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Magento\Framework\App\Config\Storage\WriterInterface $configWriter,
        \Magento\Framework\Filesystem\Io\File $fileSystem,
        \Magento\Framework\Filesystem\DirectoryList $directoryList,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->csvReader = $sampleDataContext->getCsvReader();
        $this->resource = $resourceConnection;
        $this->configWriter = $configWriter;
        $this->fileSystem = $fileSystem;
        $this->directoryList = $directoryList;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    public function setOutput($output)
    {
        $this->output = $output;

        return $this;
    }

    /**
     * Clear all settings in database contained in parameters
     *
     * @param array $settings
     * @return $this
     */
    protected function clearCurrentSettings($settings)
    {
        $paths = [];
        $connection = $this->resource->getConnection();

        foreach ($settings as $path => $setting) {
            $paths[] = $path;
        }

        $connection->delete(
            $connection->getTableName('core_config_data'),
            ['path IN(?)' => $paths]
        );

        return $this;
    }

    /**
     * Set xml project settings (project name and email) from cli arguments
     *
     * @param array $args
     * @param array $settings
     * @return \Absolunet\Cli\Model\Settings
     */
    protected function setXmlProjectSettings($args, &$settings)
    {
        $settingsFile = $this->fileSystem->dirname(__DIR__) . '/csv/settings/default.csv';

        if ($this->fileSystem->fileExists($this->directoryList->getPath('etc') . '/absolunet/cli/csv/settings/custom.csv')) {
            $settingsFile = $this->fileSystem->dirname(__DIR__) . '/csv/settings/default.csv';
        }

        $rows = $this->csvReader->getData($settingsFile);
        array_shift($rows);

        foreach ($rows as $row) {
            $settings[$row[2]] = $row[1];
        }

        if (isset($args['project_name']) && !empty($args['project_name'])) {
            $settings['general/store_information/name'] = $args['project_name'];
            $settings['trans_email/ident_general/name'] = $args['project_name'];
            $settings['trans_email/ident_sales/name'] = $args['project_name'];
            $settings['trans_email/ident_support/name'] = $args['project_name'];
            $settings['design/head/default_title'] = $args['project_name'];
            $settings['design/header/logo_alt'] = $args['project_name'];
            $settings['design/email/logo_alt'] = $args['project_name'];
        }

        if (isset($args['support_email']) && !empty($args['support_email'])) {
            $settings['trans_email/ident_general/email'] = $args['support_email'];
            $settings['trans_email/ident_sales/email'] = $args['support_email'];
            $settings['trans_email/ident_support/email'] = $args['support_email'];
            $settings['contact/email/recipient_email'] = $args['support_email'];
            $settings['catalog/productalert_cron/error_email'] = $args['support_email'];
        }

        return $this;
    }

    /**
     * Set xml country settings from csv file
     *
     * @param array $args
     * @param array $settings
     * @return mixed
     */
    protected function setXmlCountrySettings($args, &$settings)
    {
        $settingsFile = $this->fileSystem->dirname(__DIR__) . '/csv/settings/' . strtolower($args['country']) . '.csv';

        if ($this->fileSystem->fileExists($this->directoryList->getPath('etc') . '/absolunet/cli/csv/settings/' . strtolower($args['country']) . '.csv')) {
            $settingsFile = $this->directoryList->getPath('etc') . '/absolunet/cli/csv/settings/' . strtolower($args['country']) . '.csv';
        }

        $rows = $this->csvReader->getData($settingsFile);
        array_shift($rows);

        foreach ($rows as $row) {
            $settings[$row[2]] = $row[1];
        }

        if (isset($settings['shipping/origin/country_id'])) {
            $settings['general/country/default'] = $settings['shipping/origin/country_id'];
            $settings['general/country/allow'] = $settings['shipping/origin/country_id'];
        }

        if (isset($settings['currency/options/base'])) {
            $settings['currency/options/default'] = $settings['currency/options/base'];
            $settings['currency/options/allow'] = $settings['currency/options/base'];
        }

        return $this;
    }

    /**
     * Update default Magento website, store and store view name
     *
     * @param array $args
     * @return $this
     */
    protected function updateDefaultName($args)
    {
        $connection = $this->resource->getConnection();
        $connection->update(
            $connection->getTableName('store_website'),
            ['name' => $args['project_name'] . ' Website'],
            ['code = ?' => 'base']
        );
        $connection->update(
            $connection->getTableName('store_group'),
            ['name' => $args['project_name'] . ' Store'],
            ['group_id = ?' => '1']
        );
        $connection->update(
            $connection->getTableName('store'),
            [
                'code' => 'en',
                'name' => 'English',
            ],
            ['store_id = ?' => '1']
        );

        return $this;
    }

    /**
     * Enable a theme by its code
     *
     * @param array $args
     * @param array $settings
     * @return \Absolunet\Cli\Model\Settings
     */
    protected function enableTheme($args, &$settings)
    {
        $themeId = 1; // Blank
        $connection = $this->resource->getConnection();
        $select = $connection->select()
                             ->from(['t' => 'theme'], 'theme_id')
                             ->where('code = ?', $args['theme_code']);
        $themeDatas = $select->query()->fetch();

        if ($themeDatas) {
            $themeId = $themeDatas['theme_id'];
        }

        $settings['design/theme/theme_id'] = $themeId;

        return $this;
    }

    /**
     * Install default Absolunet Magento settings
     *
     * @param array $args
     * @param array $settings
     * @return bool
     */
    public function install($args, $settings)
    {
        $this->output->writeln('Setting project settings ...');
        $this->setXmlProjectSettings($args, $settings);
        $this->output->writeln('Project settings set');

        $this->output->writeln('Setting country settings ...');
        $this->setXmlCountrySettings($args, $settings);
        $this->output->writeln('Country settings set');

        if (isset($args['keep_settings']) && $args['keep_settings'] === '0') {
            $this->output->writeln('Clearing current settings ...');
            $this->clearCurrentSettings($settings);
            $this->output->writeln('Current settings cleared');
        }

        if (isset($args['theme_code']) && !empty($args['theme_code'])) {
            $this->output->writeln('Enable ' . $args['theme_code'] . ' theme ...');
            $this->enableTheme($args, $settings);
            $this->output->writeln($args['theme_code'] . ' theme enabled');
        }

        if (isset($args['project_name']) && !empty($args['project_name'])) {
            $this->output->writeln('Updating default names ...');
            $this->updateDefaultName($args);
            $this->output->writeln('Default names updated');
        }

        $this->output->writeln('Installing settings ...');
        foreach ($settings as $path => $setting) {
            $this->configWriter->save($path, $setting);
        }
        $this->output->writeln('Settings has been installed');

        return true;
    }
}
