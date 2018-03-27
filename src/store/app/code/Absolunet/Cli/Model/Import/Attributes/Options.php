<?php

/**
 * @author     Alexandre Poirier <apoirier@absolunet.com>
 * @author     Mathieu Gervais <mgervais@absolunet.com>
 * @copyright  Copyright (c) 2016 Absolunet (http://www.absolunet.com)
 * @link       http://www.absolunet.com
 */
namespace Absolunet\Cli\Model\Import\Attributes;

use Magento\Framework\File\Csv;
use Magento\Catalog\Api\ProductAttributeRepositoryInterface;
use Magento\Eav\Model\Entity\Attribute\Source\TableFactory;
use Magento\Eav\Api\AttributeOptionManagementInterface;
use Magento\Eav\Api\Data\AttributeOptionLabelInterfaceFactory;
use Magento\Eav\Api\Data\AttributeOptionInterfaceFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Store\Model\StoreRepository;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Framework\Filesystem\Io\File;

class Options
{
    /** @var ProductAttributeRepositoryInterface */
    protected $attributeRepository;

    /** @var TableFactory */
    protected $tableFactory;

    /** @var AttributeOptionManagementInterface */
    protected $attributeOptionManagement;

    /** @var AttributeOptionLabelInterfaceFactory */
    protected $optionLabelFactory;

    /** @var AttributeOptionInterfaceFactory */
    protected $optionFactory;

    /** @var StoreManagerInterface */
    protected $storeManager;

    /** @var StoreRepository */
    protected $storeRepository;

    /** @var array */
    protected $attributeValues;

    /** @var Csv */
    protected $csv;
    
    /** @var  string */
    protected $entity;

    /**  @var File */
    protected $fileSystem;

    /**
     * Options constructor.
     * @param ProductAttributeRepositoryInterface $attributeRepository
     * @param TableFactory $tableFactory
     * @param AttributeOptionManagementInterface $attributeOptionManagement
     * @param AttributeOptionLabelInterfaceFactory $optionLabelFactory
     * @param AttributeOptionInterfaceFactory $optionFactory
     * @param StoreManagerInterface $storeManager
     * @param StoreRepository $storeRepository
     * @param Csv $csv
     * @param File $fileSystem
     */
    public function __construct(
        ProductAttributeRepositoryInterface $attributeRepository,
        TableFactory $tableFactory,
        AttributeOptionManagementInterface $attributeOptionManagement,
        AttributeOptionLabelInterfaceFactory $optionLabelFactory,
        AttributeOptionInterfaceFactory $optionFactory,
        StoreManagerInterface $storeManager,
        StoreRepository $storeRepository,
        Csv $csv,
        File $fileSystem
    ) {
        $this->attributeRepository       = $attributeRepository;
        $this->tableFactory              = $tableFactory;
        $this->attributeOptionManagement = $attributeOptionManagement;
        $this->optionLabelFactory        = $optionLabelFactory;
        $this->optionFactory             = $optionFactory;
        $this->storeRepository           = $storeRepository;
        $this->storeManager              = $storeManager;
        $this->csv                       = $csv;
        $this->fileSystem                = $fileSystem;
    }

    /**
     * @param string          $file
     * @param OutputInterface $output
     *
     * @throws \Exception
     */
    public function run($file, $output)
    {
        if ($this->fileSystem->fileExists($file)) {
            $stores   = $this->storeManager->getStores();
            $data     = $this->getCsvData($file);
            $i        = 0;
            $key      = array();
            $attrSeen = array();

            foreach ($data as $row) {
                if ($i == 0) {
                    $key = array_values($row);
                    $i++;

                    $storeLabelAssociativeArray = $this->getStoreLabelAssociativeArray($key);
                    if (!is_array($storeLabelAssociativeArray)) {
                        $output->writeln("<error>Attribute label(s) provided not associated to existing store code: {$storeLabelAssociativeArray}</error>");

                        break;
                    }

                    continue;
                }

                if (count($row) != count($key)) {
                    throw new \Exception(
                        'Number of keys does not match number of index'
                    );
                }

                $row = array_combine($key, $row);

                if (!in_array($row['attribute_code'], $attrSeen)) {
                    array_push($attrSeen, $row['attribute_code']);
                    $output->writeln("Starting to add option for attribute: {$row['attribute_code']}");
                }

                $optionId = $this->getOptionId($row['attribute_code'], $row['admin']);

                if ($optionId === false) {
                    $optionStore = array();

                    /** @var \Magento\Eav\Model\Entity\Attribute\OptionLabel $optionLabel */
                    $optionLabel = $this->optionLabelFactory->create();
                    $optionLabel->setStoreId(0);
                    $optionLabel->setLabel($row['admin']);

                    $optionStore[] = $optionLabel;

                    foreach ($stores as $store) {
                        /** @var \Magento\Eav\Model\Entity\Attribute\OptionLabel $optionLabel */
                        $optionStoreLabel = $this->optionLabelFactory->create();
                        $optionStoreLabel->setStoreId($store->getId());

                        if (array_key_exists($store->getCode(), $row)) {
                            $optionStoreLabel->setLabel($row[$store->getCode()]);
                        }

                        $optionStore[] = $optionStoreLabel;
                    }

                    $option = $this->optionFactory->create();
                    $option->setLabel($optionLabel);
                    $option->setStoreLabels($optionStore);
                    $option->setSortOrder(0);
                    $option->setIsDefault(false);

                    $this->attributeOptionManagement->add(
                        $this->entity,
                        $this->getAttribute($row['attribute_code'])->getAttributeId(),
                        $option
                    );

                    // Get the inserted ID. Should be returned from the installer, but it isn't.
                    $optionId = $this->getOptionId($row['attribute_code'], $row['admin'], true);

                    if ($optionId === false) {
                        $output->writeln("<error>Option ({$row['admin']}) was not created</error>");
                    } else {
                        $output->writeln("<info>Option ({$row['admin']}) has successfully been created</info>");
                    }
                } else {
                    $output->writeln("<comment>Option ({$row['admin']}) already exist</comment>");
                }

                $i++;
            }
        } else {
            throw new \Exception("File {$file} does not exist");
        }
    }

    /**
     * @param string $attributeCode
     * @param string $adminLabel
     * @param bool   $force
     *
     * @return bool
     */
    protected function getOptionId($attributeCode, $adminLabel, $force = false)
    {
        $attribute = $this->getAttribute($attributeCode);
        $attribute->setStoreId(0);

        if ($force === true
            || !isset($this->attributeValues[$attribute->getAttributeId()])
        ) {
            $this->attributeValues[$attribute->getAttributeId()] = [];

            /** @var \Magento\Eav\Model\Entity\Attribute\Source\Table $sourceModel */
            $sourceModel = $this->tableFactory->create();
            $sourceModel->setAttribute($attribute);

            foreach ($sourceModel->getAllOptions() as $option) {
                $this->attributeValues[$attribute->getAttributeId()]
                    [$option['label']] = $option['value'];
            }
        }

        if (isset($this->attributeValues[$attribute->getAttributeId()][$adminLabel])) {
            return $this->attributeValues[$attribute->getAttributeId()][$adminLabel];
        }

        return false;
    }

    /**
     * @param string $entity
     *
     * @return Attribute
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * @param string $attributeCode
     *
     * @return \Magento\Catalog\Api\Data\ProductAttributeInterface
     */
    public function getAttribute($attributeCode)
    {
        return $this->attributeRepository->get($attributeCode);
    }

    /**
     * @param array $keys
     *
     * @return array|string
     */
    protected function getStoreLabelAssociativeArray($keys)
    {
        $storeList        = $this->getStoreList();
        $associativeArray = [];
        $invalidKeys      = [];

        foreach ($keys as $key) {
            if (substr($key, 0, 16) === 'attribute_label_') {
                $labelStoreCode = substr($key, 16);
                if (array_key_exists($labelStoreCode, $storeList)) {
                    $storeId                = $storeList[$labelStoreCode];
                    $associativeArray[$key] = $storeId;
                } else {
                    $invalidKeys[] = $key;
                }
            }
        }

        if (empty($invalidKeys)) {
            return $associativeArray;
        } else {
            return implode(',', $invalidKeys);
        }
    }

    /**
     * @return array
     */
    protected function getStoreList()
    {
        $stores    = $this->storeRepository->getList();
        $storeList = array();
        foreach ($stores as $store) {
            $storeList[$store['code']] = $store['store_id'];
        }

        return $storeList;
    }

    /**
     * @param string $file
     *
     * @return array
     */
    protected function getCsvData($file)
    {
        $this->csv->setDelimiter(',');
        $this->csv->setEnclosure('"');

        return $this->csv->getData($file);
    }
}
