<?php

namespace Absolunet\Cli\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Magento\Framework\App\Area;

class Archive extends Command
{
    /** @var \Magento\Framework\App\State */
    protected $appState;

    /**
     * Archive constructor.
     * @param \Magento\Framework\App\State $appState
     * @param \Magento\SalesArchive\Model\ArchiveFactory $archiveFactory
     */
    public function __construct(
        \Magento\Framework\App\State $appState,
        \Magento\SalesArchive\Model\ArchiveFactory $archiveFactory
    ) {
        $this->appState = $appState;
        $this->archiveFactory = $archiveFactory;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('boutik:archive');
        $this->setDescription('Run archiving (orders, invoices, shipments, rma) without waiting cronjob');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->appState->setAreaCode(Area::AREA_ADMINHTML);

        $output->writeln('Archiving...');
        $this->archiveFactory->create()->archiveOrders();
        $output->writeln('Done.');
    }
}
