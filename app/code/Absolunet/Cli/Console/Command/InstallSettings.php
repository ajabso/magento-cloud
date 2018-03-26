<?php

namespace Absolunet\Cli\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Magento\Framework\App\Area;

class InstallSettings extends Command
{
    /** @var \Magento\Framework\App\State */
    protected $appState;

    /** @var \Absolunet\Cli\Api\Data\SettingsInterfaceFactory */
    protected $settingsModel;

    /** @var array */
    protected $xmlSettings = [];

    /**
     * InstallSettings constructor.
     * @param \Magento\Framework\App\State $appState
     * @param \Absolunet\Cli\Api\Data\SettingsInterfaceFactory $settingsModel
     */
    public function __construct(
        \Magento\Framework\App\State $appState,
        \Absolunet\Cli\Api\Data\SettingsInterfaceFactory $settingsModel
    ) {
        $this->appState = $appState;
        $this->settingsModel = $settingsModel;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('boutik:install:settings');
        $this->setDescription('Install default Boutik Magento settings');
        $this->addArgument('country', InputArgument::REQUIRED, 'Default Magento country. Country ISO ALPHA-2 Code');
        $this->addArgument('project_name', InputArgument::OPTIONAL, 'Project name. Ex: Stokes');
        $this->addArgument('support_email', InputArgument::OPTIONAL, 'Default support email. '
            . '.Ex: magento@absolunet.com');
        $this->addArgument('keep_current_settings', InputArgument::OPTIONAL, 'Keep current settings. Ex: 1 or 0. Default is 1.');
        $this->addArgument('theme_code', InputArgument::OPTIONAL, 'Theme code to enable by default. Ex: Boutik/boutik');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->appState->setAreaCode(Area::AREA_ADMINHTML);

        $result = $this->settingsModel->create()
                        ->setOutput($output)
                        ->install($input->getArguments(), $this->xmlSettings);

        if (!is_bool($result)) {
            $output->writeln('ERROR: ' . $result);
        }
    }
}
