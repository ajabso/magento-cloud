<?php

/**
 * @author     Alexandre Poirier <apoirier@absolunet.com>
 * @author     Mathieu Gervais <mgervais@absolunet.com>
 * @copyright  Copyright (c) 2016 Absolunet (http://www.absolunet.com)
 * @link       http://www.absolunet.com
 */

namespace Absolunet\Cli\Console\Command;

use Magento\Framework\App\Filesystem\DirectoryList;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Absolunet\Cli\Model\Import\Attributes\Options;
use Absolunet\Cli\Logger\Logger;
use Absolunet\Cli\Helper\Data;

class ImportAttributeOptionCommand extends Command
{
    /** @var Options */
    protected $options;

    /** @var DirectoryList */
    protected $dir;

    protected $log;

    /** @var String */
    protected $folder;

    /** @var Data */
    protected $helper;

    /**
     * ImportAttributeOptionCommand constructor.
     *
     * @param Options       $options
     * @param DirectoryList $directoryList
     * @param Logger        $logger
     * @param Data          $helper
     */
    public function __construct(
        Options $options,
        DirectoryList $directoryList,
        Logger $logger,
        Data $helper
    ) {
        $this->options = $options;
        $this->dir     = $directoryList;
        $this->log     = $logger;
        $this->helper  = $helper;
        $this->folder  = $this->helper->getCsvImportPath();

        parent::__construct();
    }

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName('boutik:import:attribute_option')
            ->setDescription('Import new attribute options')
            ->setAliases(array('iao'))
            ->addArgument(
                'filename',
                InputArgument::OPTIONAL,
                'file name of the csv to import Ex.: attributeOptions.csv',
                'attributeOptions.csv'
            )
            ->addArgument(
                'entity',
                InputArgument::OPTIONAL,
                'Import entity type (product or category)'
            );
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $start = $this->helper->getTime();
        $output->writeln('----- START -----');

        try {
            $file     = $input->getArgument('filename');
            $folder    = (strpos($file, DIRECTORY_SEPARATOR) !== false) ? '' : $this->folder;
            $optModel = $this->options;

            $entityArg = $input->getArgument('entity');

            if ($entityArg == 'category') {
                $optModel->setEntity(\Magento\Catalog\Model\Category::ENTITY);
            } elseif ($entityArg == 'product' || $entityArg == null) {
                $optModel->setEntity(\Magento\Catalog\Model\Product::ENTITY);
            } else {
                throw new \Exception('Entity must be product or category');
            }

            $optModel->run($folder . $file, $output);
        } catch (\Exception $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
            $this->log->addError($e);
        }

        $end      = $this->helper->getTime();
        $execTime = $this->helper->formatTime($end - $start);

        $output->writeln('----- FINISHED in ' . $execTime .'  -----');
    }
}
