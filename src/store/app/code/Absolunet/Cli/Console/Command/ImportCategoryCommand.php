<?php
/**
 * @author     Mathieu Gervais <mgervais@absolunet.com>
 * @copyright  Copyright (c) 2018 Absolunet (http://www.absolunet.com)
 * @link       http://www.absolunet.com
 */

namespace Absolunet\Cli\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Absolunet\Cli\Model\Import\Category;
use Absolunet\Cli\Logger\Logger;
use Absolunet\Cli\Helper\Data;

class ImportCategoryCommand extends Command
{
    /** @var Category */
    protected $category;

    /** @var Logger */
    protected $log;

    /** @var  String */
    protected $folder;

    /** @var Data */
    protected $helper;

    /**
     * ImportAttributeCommand constructor.
     *
     * @param Category  $category
     * @param Logger    $logger
     * @param Data      $helper
     */
    public function __construct(
        Category $category,
        Logger $logger,
        Data $helper
    ) {
        $this->category   = $category;
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
        $this->setName('boutik:import:category')
            ->setDescription('Import new category')
            ->setAliases(array('ic'))
            ->addArgument(
                'filename',
                InputArgument::OPTIONAL,
                'file name of the csv to import Ex.: category.csv',
                'category.csv'
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
            $file = $input->getArgument('filename');
            $folder = (strpos($file, DIRECTORY_SEPARATOR) !== false) ? '' : $this->folder;
            $this->category->run($folder . $file, $output);
        } catch (\Exception $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
            $this->log->addError($e);
        }

        $end      = $this->helper->getTime();
        $execTime = $this->helper->formatTime($end - $start);

        $output->writeln('----- FINISHED in ' . $execTime .'  -----');
    }
}
