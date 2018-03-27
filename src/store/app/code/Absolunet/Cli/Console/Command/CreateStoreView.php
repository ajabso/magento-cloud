<?php

namespace Absolunet\Cli\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Magento\Framework\App\Area;

class CreateStoreView extends Command
{
    /** @var \Magento\Framework\App\State */
    protected $appState;

    /** @var \Absolunet\Cli\Api\Data\StoreViewInterfaceFactory */
    protected $storeViewModel;

    /** Default store id */
    const DEFAULT_STORE = 1;

    /**
     * CreateStoreView constructor.
     * @param \Magento\Framework\App\State $appState
     * @param \Absolunet\Cli\Api\Data\StoreViewInterfaceFactory $storeViewModel
     */
    public function __construct(
        \Magento\Framework\App\State $appState,
        \Absolunet\Cli\Api\Data\StoreViewInterfaceFactory $storeViewModel
    ) {
        $this->appState = $appState;
        $this->storeViewModel = $storeViewModel;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('boutik:create:store-view');
        $this->setDescription('Create a new store view according its locale');
        $this->addArgument('locale', InputArgument::REQUIRED, 'Locale(s) of store view(s) to create. '
            . '.Comma separated. Ex: fr_CA,en_CA,en_US');
        $this->addArgument('store_id', InputArgument::OPTIONAL, 'Parent Store ID. Default is ' . self::DEFAULT_STORE);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->appState->setAreaCode(Area::AREA_ADMINHTML);
        $args = $input->getArguments();
        $storeID = isset($args['store_id']) ? $args['store_id'] : self::DEFAULT_STORE;
        $locales = explode(',', $args['locale']);

        foreach ($locales as $locale) {
            $output->writeln('Creating new store view ' . $locale . ' ...');
            $result = $this->storeViewModel->create()->create($locale, $storeID);

            if (is_bool($result)) {
                $output->writeln('Store view ' . $locale . ' has been created');
            } else {
                $output->writeln('ERROR: ' . $result);
            }
        }

        $output->writeln('All store views requested have been processed');
    }
}
