<?php

namespace Absolunet\Cli\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class DeleteTestDatas extends Command
{
    /** @var \Absolunet\Cli\Api\Data\TestDatasInterfaceFactory  */
    protected $testDatasModel;

    protected $allowedDataCode = [
        'all', 'order', 'quote', 'customer', 'shipping', 'invoice', 'creditmemo', 'rma', 'gift_card', 'gift_registry',
        'product', 'category', 'review'
    ];

    /**
     * DeleteTestDatas constructor.
     * @param \Absolunet\Cli\Api\Data\TestDatasInterfaceFactory $testDatasModel
     */
    public function __construct(
        \Absolunet\Cli\Api\Data\TestDatasInterfaceFactory $testDatasModel
    ) {
        $this->testDatasModel = $testDatasModel;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('boutik:delete:test-datas');
        $this->setDescription('Delete test datas by code types');
        $this->addArgument('data_code', InputArgument::REQUIRED, 'Data code(s) to delete '
            .' [all|order|quote|customers|shipping|invoice|creditmemo|rma|gift_card|gift_registry|product|category|review].'
            . ' Comma separated.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $args = explode(',', $input->getArgument('data_code'));

        if (count($args) > 1 && in_array('all', $args)) {
            $args = ['all'];
        }

        foreach ($args as $code) {
            if (!in_array($code, $this->allowedDataCode)) {
                return $output->writeln('ERROR: ' . $code . ' is not a valid data_code');
            }
        }

        foreach ($args as $code) {
            $output->writeln('Deleting ' . $code . ' datas ...');
            $result = $this->testDatasModel->create()->deleteByCode(trim($code));

            if (is_bool($result)) {
                $output->writeln(ucfirst($code) . ' datas has been deleted');
            } else {
                $output->writeln('ERROR: ' . $result);
            }
        }

        $output->writeln('All data codes have been processed');
    }
}
