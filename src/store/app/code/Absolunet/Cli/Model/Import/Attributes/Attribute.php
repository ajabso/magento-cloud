<?php

/**
 * @author     Alexandre Poirier <apoirier@absolunet.com>
 * @author     Jonathan Bernard <jbernard@absolunet.com>
 * @author     Cyril Ekoule <cekoule@absolunet.com>
 *
 * @copyright  Copyright (c) 2017 Absolunet (http://www.absolunet.com)
 * @link       http://www.absolunet.com
 */

namespace Absolunet\Cli\Model\Import\Attributes;

use Symfony\Component\Console\Output\OutputInterface;
use Magento\Framework\File\Csv as Csv;
use Absolunet\Cli\Logger\Logger;
use Magento\Eav\Model\ResourceModel\Entity\Attribute as ResourceModelAttribute;
use Magento\Eav\Model\Entity\Attribute as ModelAttribute;
use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Entity\Type;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface as Scope;
use Magento\Catalog\Helper\Product as Helper;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Store\Model\StoreRepository;
use Magento\Framework\Filesystem\Io\File;

class Attribute
{
    /** @var Logger */
    protected $log;

    /** @var ResourceModelAttribute */
    protected $attributeResourceModel;

    /** @var ModelAttribute */
    protected $attributeModel;

    /** @var Helper */
    protected $productHelper;

    /** @var OutputInterface */
    protected $output;

    /** @var StoreManagerInterface */
    protected $storeManager;

    /** @var StoreRepository */
    protected $storeRepository;

    /** @var EavSetupFactory */
    protected $eavSetupFactory;

    /** @var  string */
    protected $entity;

    /** @var Type */
    protected $type;

    /** @var Csv */
    protected $csv;

    /** @var ModuleDataSetupInterface */
    protected $setup;

    /**  @var File */
    protected $fileSystem;

    /**
     * Attribute constructor.
     * @param Logger $logger
     * @param ModelAttribute $attributeModel
     * @param ResourceModelAttribute $attributeResourceModel
     * @param Helper $helper
     * @param StoreManagerInterface $storeManager
     * @param StoreRepository $storeRepository
     * @param EavSetupFactory $eavSetupFactory
     * @param Type $type
     * @param Csv $csv
     * @param ModuleDataSetupInterface $setup
     * @param File $fileSystem
     */
    public function __construct(
        Logger $logger,
        ModelAttribute $attributeModel,
        ResourceModelAttribute $attributeResourceModel,
        Helper $helper,
        StoreManagerInterface $storeManager,
        StoreRepository $storeRepository,
        EavSetupFactory $eavSetupFactory,
        Type $type,
        Csv $csv,
        ModuleDataSetupInterface $setup,
        File $fileSystem
    ) {
        $this->log                    = $logger;
        $this->attributeModel         = $attributeModel;
        $this->attributeResourceModel = $attributeResourceModel;
        $this->productHelper          = $helper;
        $this->storeManager           = $storeManager;
        $this->storeRepository        = $storeRepository;
        $this->eavSetupFactory        = $eavSetupFactory;
        $this->type                   = $type;
        $this->csv                    = $csv;
        $this->setup                  = $setup;
        $this->fileSystem             = $fileSystem;
    }

    /**
     * @param String          $file
     * @param OutputInterface $output
     *
     * @throws \Exception
     */
    public function run($file, $output)
    {
        if ($this->fileSystem->fileExists($file)) {
            $this->output = $output;
            $typeId       = $this->type->loadByCode($this->entity)->getEntityTypeId();

            $this->setup->startSetup();

            $eavSetup = $this->eavSetupFactory->create();

            $data                       = $this->getCsvData($file);
            $key                        = array();
            $i                          = 0;
            $o                          = 0;
            $storeLabelAssociativeArray = array();

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

                if (count($key) != count($row)) {
                    throw new \Exception('Number of keys does not match number of index');
                }

                $row = array_combine($key, $row);

                $this->validateRow($row);

                $attributeCode = $row['attribute_code'];
                $attrId        = $eavSetup->getAttributeId($typeId, $attributeCode);

                try {
                    if ($attrId === false) {
                        $this->addAttribute($row, $eavSetup, $storeLabelAssociativeArray);
                        $output->writeln("<info>{$attributeCode} was successfully created</info>");
                    } else {
                        $output->writeln("<comment>Attribute already exist with code : {$attributeCode}. Id is : {$attrId}</comment>");
                    }

                    if ($this->entity == Product::ENTITY) {
                        if (!isset($row['attribute_set']) || $row['attribute_set'] == '') {
                            $row['attribute_set'] = "Default";
                        }

                        $attributeSetNames = explode(',', $row['attribute_set']);
                        foreach ($attributeSetNames as $attributeSetName) {
                            $this->addAttributeToSet($eavSetup, $typeId, $attributeSetName, $row);
                        }
                    }

                    $o++;
                } catch (\Magento\Framework\Exception\LocalizedException $e) {
                    $output->writeln('<error>' . $e->getMessage() . " for attribute code : {$attributeCode} at row {$i} </error>");
                }

                $i++;

                $output->writeln('-----------------');
            }

            $nbAttributes = $i - 1;
            $output->writeln("Line process {$o}/{$nbAttributes}");
        } else {
            throw new \Exception("File {$file} does not exist");
        }
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

    /**
     * @param array    $row
     * @param EavSetup $eavSetup
     * @param array    $storeLabelAssociativeArray
     *
     * @return void
     */
    protected function addAttribute($row, $eavSetup, $storeLabelAssociativeArray)
    {
        /** First provided label is used as frontend_label */
        $row['frontend_label'] = null;
        if (is_array($storeLabelAssociativeArray) && !empty($storeLabelAssociativeArray)) {
            reset($storeLabelAssociativeArray);
            $row['frontend_label'] = $row[key($storeLabelAssociativeArray)];
        }

        $data = $this->formatData($row);
        $eavSetup->addAttribute(
            $this->entity,
            $row['attribute_code'],
            $data
        );

        $typeId = $this->type->loadByCode(Product::ENTITY)->getEntityTypeId();
        $attrId = $eavSetup->getAttributeId($typeId, $row['attribute_code']);

        if ($attrId) {
            $this->attributeResourceModel->load($this->attributeModel, $attrId);
            $storeLabels = $this->attributeModel->getStoreLabels();

            if (!empty($storeLabelAssociativeArray)) {
                foreach ($storeLabelAssociativeArray as $labelKey => $storeId) {
                    $storeLabels[$storeId] = $row[$labelKey];
                }

                $this->attributeModel->setData('store_labels', $storeLabels);
                $this->attributeResourceModel->save($this->attributeModel);
            }
        }
    }

    /**
     * @param array $row
     *
     * @return array
     */
    protected function formatData($row)
    {
        switch ($row['scope']) {
            case 'store':
                $scope = Scope::SCOPE_STORE;
                break;

            case 'website':
                $scope = Scope::SCOPE_WEBSITE;
                break;

            default:
                $scope = Scope::SCOPE_GLOBAL;
                break;
        }

        $required             = trim(strtolower($row['required']), ' ');
        $default              = trim(strtolower($row['default']), ' ');
        $searchable           = trim(strtolower($row['searchable']), ' ');
        $filterable           = trim(strtolower($row['filterable']), ' ');
        $visible              = trim(strtolower($row['visible']), ' ');
        $unique               = trim(strtolower($row['unique']), ' ');
        $usedInProductListing = trim(
            strtolower($row['used_in_product_listing']),
            ' '
        );

        return array(
            'type'                    => $this->attributeModel->getBackendTypeByInput($row['input']),
            'backend'                 => $this->productHelper->getAttributeBackendModelByInputType($row['input']),
            'frontend'                => '',
            'label'                   => ($row['frontend_label'] != false) ? $row['frontend_label'] : null,
            'input'                   => $row['input'],
            'class'                   => '',
            'source'                  => $this->productHelper->getAttributeSourceModelByInputType($row['input']),
            'global'                  => $scope,
            'visible'                 => true,
            'required'                => ($required == 'yes') ? true : false,
            'user_defined'            => true,
            'default'                 => ($default == '') ? '' : $row['default'],
            'searchable'              => ($searchable == 'yes') ? true : false,
            'filterable'              => ($filterable == 'yes') ? true : false,
            'comparable'              => false,
            'visible_on_front'        => ($visible == 'yes') ? true : false,
            'used_in_product_listing' => ($usedInProductListing == 'yes') ? true : false,
            'unique'                  => ($unique == 'yes') ? true : false,
            'apply_to'                => ''
        );
    }

    /**
     * @param array $row
     *
     * @throws \Exception
     * @return void
     */
    protected function validateRow($row)
    {
        $requiredColumns = array(
            'attribute_code',
            'required',
            'default',
            'searchable',
            'filterable',
            'visible',
            'unique',
            'scope',
            'used_in_product_listing',
            'input'
        );

        foreach ($requiredColumns as $column) {
            if (!isset($row[$column])) {
                throw new \Exception('The column "' . $column . '" is missing from the csv file.');
            }
        }
    }

    /**
     * @param EavSetup $eavSetup
     * @param int      $typeId
     * @param string   $attributeSetName
     * @param array    $row
     *
     * @return void
     */
    protected function addAttributeToSet($eavSetup, $typeId, $attributeSetName, $row)
    {
        if ($attrSetId = $eavSetup->getAttributeSetId($typeId, $attributeSetName)) {
            $attrId  = $eavSetup->getAttributeId(
                $typeId,
                $row['attribute_code']
            );

            $groupId = $this->getGroupId($eavSetup, $row, $typeId, $attrSetId);

            $eavSetup->addAttributeToSet(
                $typeId,
                $attrSetId,
                $groupId,
                $attrId
            );

            $this->output->writeln("<info>Attribute was added to set {$attributeSetName}</info>");
        } else {
            $this->output->writeln("<error>Attribute Set {$attributeSetName} does not exist</error>");
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
     * Get attribute group id.
     * Create if not exists
     *
     * @param EavSetup $eavSetup
     * @param array    $row
     * @param int      $typeId
     * @param int      $attrSetId
     *
     * @return int
     */
    protected function getGroupId($eavSetup, $row, $typeId, $attrSetId)
    {
        if (isset($row['group']) && $row['group']) {
            $eavSetup->addAttributeGroup($typeId, $attrSetId, $row['group']);
            $groupName = $row['group'];
        } else {
            $groupName = 'Product Details';
        }

        /** @var int $groupId */
        $groupId = $eavSetup->getAttributeGroupId(
            $typeId,
            $attrSetId,
            $groupName
        );

        return $groupId;
    }
}
