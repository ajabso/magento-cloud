<?php

/**
 * @author     Alexandre Poirier <apoirier@absolunet.com>
 * @copyright  Copyright (c) 2016 Absolunet (http://www.absolunet.com)
 * @link       http://www.absolunet.com
 */

namespace Absolunet\Cli\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Absolunet\Cli\Model\Import\Attributes\Attribute;
use Absolunet\Cli\Logger\Logger;
use Absolunet\Cli\Helper\Data;

class ImportAttributeCommand extends Command
{
    /** @var Attribute */
    protected $attributes;

    /** @var Logger */
    protected $log;

    /** @var  String */
    protected $folder;

    /** @var Data */
    protected $helper;

    /**
     * ImportAttributeCommand constructor.
     *
     * @param Attribute $attributes
     * @param Logger    $logger
     * @param Data      $helper
     */
    public function __construct(
        Attribute $attributes,
        Logger $logger,
        Data $helper
    ) {
        $this->attributes = $attributes;
        $this->log        = $logger;
        $this->helper     = $helper;

        $this->folder = $this->helper->getCsvImportPath();

        parent::__construct();
    }

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName('boutik:import:attribute')
            ->setDescription('Import new attribute')
            ->setAliases(array('ia'))
            ->addArgument(
                'filename',
                InputArgument::OPTIONAL,
                'file name of the csv to import Ex.: attribute.csv',
                'attributes.csv'
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
            $file      = $input->getArgument('filename');
            $folder    = (strpos($file, DIRECTORY_SEPARATOR) !== false) ? '' : $this->folder;
            $attrModel = $this->attributes;

            $entityArg = $input->getArgument('entity');
            if ($entityArg == 'category') {
                $attrModel->setEntity(\Magento\Catalog\Model\Category::ENTITY);
            } elseif ($entityArg == 'product' || $entityArg == null) {
                $attrModel->setEntity(\Magento\Catalog\Model\Product::ENTITY);
            } else {
                throw new \Exception('Entity must be product or category');
            }

            $attrModel->run($folder . $file, $output);
        } catch (\Exception $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
            $this->log->addError($e);
        }

        $end      = $this->helper->getTime();
        $execTime = $this->helper->formatTime($end - $start);

        $output->writeln('----- FINISHED in ' . $execTime .'  -----');
    }
}
