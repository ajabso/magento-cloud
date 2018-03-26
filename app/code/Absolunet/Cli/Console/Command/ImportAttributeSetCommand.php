<?php

/**
 * @author    Mackendy Jeudy <mjeudy@absolunet.com>
 * @author    Cyril Ekoule <cekoule@absolunet.com>
 * @author    Mathieu Gervais <mgervais@absolunet.com>
 * @copyright Copyright (c) 2016 Absolunet (http://www.absolunet.com)
 * @link      http://www.absolunet.com
 */

namespace Absolunet\Cli\Console\Command;

use Magento\Eav\Model\Config as EavConfig;
use Magento\Framework\Exception\AlreadyExistsException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Absolunet\Cli\Model\Import\Attributes\AttributeSet;
use Absolunet\Cli\Logger\Logger;
use Absolunet\Cli\Helper\Data;

class ImportAttributeSetCommand extends Command
{
    /** @var AttributeSet */
    protected $attributeSet;

    /** @var EavConfig */
    protected $eavConfig;

    /** @var Logger */
    protected $log;

    /** @var  String */
    protected $folder;

    /** @var Data */
    protected $helper;

    /**
     * ImportAttributeSetCommand constructor.
     * @param AttributeSet $attributeSet
     * @param EavConfig $eavConfig
     * @param Logger $logger
     * @param Data $helper
     */
    public function __construct(
        AttributeSet $attributeSet,
        EavConfig $eavConfig,
        Logger $logger,
        Data $helper
    ) {
    
        $this->attributeSet  = $attributeSet;
        $this->eavConfig     = $eavConfig;
        $this->log           = $logger;
        $this->helper        = $helper;
        $this->folder        = $this->helper->getCsvImportPath();

        parent::__construct();
    }

    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this->setName('boutik:import:attribute_set')
            ->setDescription('Import attributes set')
            ->setAliases(array('ias'))
            ->addArgument(
                'entity',
                InputArgument::REQUIRED,
                'Import entity type (ex: catalog_product)'
            )
            ->addOption(
                'filename',
                '-f',
                InputOption::VALUE_OPTIONAL,
                'File name of the csv to import Ex.: attributeSet.csv',
                'attributeSet.csv'
            )
            ->addArgument(
                'attributeSetNames',
                InputArgument::IS_ARRAY,
                'Attribute set name'
            )
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $start = $this->helper->getTime();
        $output->writeln('----- START -----');

        try {
            $file = $input->getOption('filename');
            $folder = (strpos($file, DIRECTORY_SEPARATOR) !== false) ? '' : $this->folder;
            $attributeSetNames = $input->getArgument('attributeSetNames');
            $entityTypeCode    = $input->getArgument('entity');

            if (!$this->eavConfig->getEntityType($entityTypeCode)) {
                throw new \Exception('Entity does not exist');
            }

            if ($file) {
                $filePath          = $folder . $file;
                $attributeSetNames = $this->attributeSet->readFile($filePath);
            }

            if (count($attributeSetNames) == 0) {
                throw new \Exception('No attribute sets given');
            }

            foreach ($attributeSetNames as $attributeSetName) {
                try {
                    $this->attributeSet->create($attributeSetName, $entityTypeCode);
                    $output->writeln('<info>Attribute set ' . $attributeSetName . ' imported</info>');
                } catch (AlreadyExistsException $e) {
                    $output->writeln('<error>Attribute set ' . $attributeSetName . ' already exists for ' . $entityTypeCode . '</error>');
                    $this->log->addError($e);
                }
            }
        } catch (\Exception $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
            $this->log->addError($e);
        }

        $end      = $this->helper->getTime();
        $execTime = $this->helper->formatTime($end - $start);

        $output->writeln('----- FINISHED in ' . $execTime .'  -----');
    }
}
