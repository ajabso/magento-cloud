<?php

namespace Absolunet\Cli\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Magento\Framework\App\Area;

class InstallRules extends Command
{
    /** @var \Magento\Framework\App\State */
    protected $appState;

    /** @var \Absolunet\Cli\Api\Data\RulesInterfaceFactory */
    protected $rulesModel;

    /** Default country */
    protected $defaultCountry = ['CA'];

    /**
     * InstallRules constructor.
     * @param \Magento\Framework\App\State $appState
     * @param \Absolunet\Cli\Api\Data\RulesInterfaceFactory $rulesModel
     */
    public function __construct(
        \Magento\Framework\App\State $appState,
        \Absolunet\Cli\Api\Data\RulesInterfaceFactory $rulesModel
    ) {
        $this->appState = $appState;
        $this->rulesModel = $rulesModel;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('boutik:install:tax-rules');
        $this->setDescription('Install country tax rules. Default country is ' . implode(', ', $this->defaultCountry));
        $this->addArgument('country', InputArgument::REQUIRED, 'Country ISO ALPHA-2 Code. Comma separated. Ex: ca,us,fr');
        $this->addArgument('keep_current_settings', InputArgument::OPTIONAL, 'Keep current settings. Ex: 1 or 0. Default is 1.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->appState->setAreaCode(Area::AREA_ADMINHTML);
        $countries = $this->defaultCountry;
        $keepSettings = 1;

        if (!empty($input->getArgument('country'))) {
            $countries = explode(',', $input->getArgument('country'));
        }

        if ($input->getArgument('keep_current_settings') === '0') {
            $keepSettings = 0;
        }

        foreach ($countries as $country) {
            $output->writeln('Installing ' . $country . ' tax rules ...');
            $result = $this->rulesModel->create()
                            ->setOutput($output)
                            ->install(trim($country), $keepSettings);
            if (is_bool($result)) {
                $output->writeln($country . ' tax rules has been installed');
            } else {
                $output->writeln('ERROR: ' . $result);
            }
        }

        $output->writeln('All country tax rules requested have been processed');
    }
}
