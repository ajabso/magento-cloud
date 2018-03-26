<?php

namespace Absolunet\Cli\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class InstallRates extends Command
{
    /** @var \Absolunet\Cli\Api\Data\RatesInterfaceFactory */
    protected $ratesModel;

    /** Default country */
    protected $defaultCountry = ['CA'];

    /**
     * InstallRates constructor.
     * @param \Absolunet\Cli\Api\Data\RatesInterfaceFactory $ratesModel
     */
    public function __construct(
        \Absolunet\Cli\Api\Data\RatesInterfaceFactory $ratesModel
    ) {
        $this->ratesModel = $ratesModel;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('boutik:install:tax-rates');
        $this->setDescription('Install country tax rates. Default country is ' . implode(', ', $this->defaultCountry));
        $this->addArgument('country', InputArgument::REQUIRED, 'Country ISO ALPHA-2 Code. Comma separated. Ex: ca,us,fr');
        $this->addArgument('keep_current_settings', InputArgument::OPTIONAL, 'Keep current settings. Ex: 1 or 0. Defaut is 1.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $countries = $this->defaultCountry;
        $keepSettings = 1;

        if (!empty($input->getArgument('country'))) {
            $countries = explode(',', $input->getArgument('country'));
        }

        if ($input->getArgument('keep_current_settings') === '0') {
            $keepSettings = 0;
        }

        foreach ($countries as $country) {
            $output->writeln('Installing ' . $country . ' tax rates ...');
            $result = $this->ratesModel->create()
                            ->setOutput($output)
                            ->install($country, $keepSettings);
            if (is_bool($result)) {
                $output->writeln($country . ' tax rates has been installed');
            } else {
                $output->writeln('ERROR: ' . $result);
            }
        }

        $output->writeln('All country tax rates requested have been processed');
    }
}
