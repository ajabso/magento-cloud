<?php
/**
 * @author     Mathieu Gervais <mgervais@absolunet.com>
 * @author     Cyril Ekoule <cekoule@absolunet.com>
 * @copyright  Copyright (c) 2018 Absolunet (http://www.absolunet.com)
 * @link       http://www.absolunet.com
 */

namespace Absolunet\Cli\Model\Import;

use Absolunet\Cli\Logger\Logger;
use Magento\Framework\File\Csv;
use Magento\Framework\Filesystem\Io\File;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Eav\Api\AttributeRepositoryInterface;
use Magento\Store\Model\StoreManagerInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Category
{
    /** @var Logger */
    protected $log;

    /** @var Csv */
    protected $csv;

    /**  @var File */
    protected $fileSystem;

    /** @var CategoryRepositoryInterface */
    protected $categoryRepository;

    /** @var CategoryCollectionFactory */
    protected $categoryCollectionFactory;

    /** @var AttributeRepositoryInterface */
    protected $attributeRepository;

    /** @var OutputInterface */
    protected $output;

    /**
     * Category constructor.
     *
     * @param Logger                       $logger
     * @param Csv                          $csv
     * @param File                         $fileSystem
     * @param CategoryCollectionFactory    $categoryCollectionFactory
     * @param AttributeRepositoryInterface $attributeRepository
     * @param StoreManagerInterface        $storeManager
     * @param CategoryRepositoryInterface  $categoryRepository
     */
    public function __construct(
        Logger $logger,
        Csv $csv,
        File $fileSystem,
        CategoryCollectionFactory $categoryCollectionFactory,
        AttributeRepositoryInterface $attributeRepository,
        StoreManagerInterface $storeManager,
        CategoryRepositoryInterface $categoryRepository
    ) {
        $this->log                       = $logger;
        $this->csv                       = $csv;
        $this->fileSystem                = $fileSystem;
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->attributeRepository       = $attributeRepository;
        $this->storeManager              = $storeManager;
        $this->categoryRepository        = $categoryRepository;
    }

    /**
     * @param String          $file
     * @param OutputInterface $output
     *
     * @throws \Exception
     */
    public function run($file, $output)
    {
        $this->output = $output;

        if ($this->fileSystem->fileExists($file)) {
            $data   = $this->getCsvData($file);
            $header = array_shift($data);

            foreach ($data as $line => $row) {
                $lineNumber = $line + 1;

                if (count($header) != count($row)) {
                    throw new \Exception("Number of data values does not match number of header keys on line $lineNumber");
                }

                $data = [];
                foreach ($row as $key => $value) {
                    $data[$header[$key]] = $value;
                }

                $this->saveCategory($data);
            }
        }
    }

    /**
     * @param array $row
     *
     * @throws \Exception
     */
    protected function saveCategory($row)
    {
        $this->output->write('Saving category "' . $row['name'] . '" : ');

        $this->storeManager->getStore()->setId(0);

        $parentId       = null;
        $parentCategory = $this->getParentCategory($row);

        $data = [
            'is_active'  => $row['is_active'] ?? 0,
            'updated_at' => $row['updated_at'],
            'parent_id'  => null
        ];

        if ($parentCategory->getId()) {
            $parentId          = $parentCategory->getId();
            $data['parent_id'] = $parentId;

            unset($row['parent_name']);
        } else {
            throw new \Exception('Parent not found for category: "' . $row['name'] . '"');
        }

        /** @var \Magento\Catalog\Model\Category $category */
        $category = $this->loadCategoryByName($row['admin_name'], $row['level']);

        unset($row['admin_name']);

        foreach ($row as $key => $value) {
            if (!array_key_exists($key, $data) && !in_array($key, array('store_id'))) {
                try {
                    if ($this->attributeRepository
                        ->get(\Magento\Catalog\Api\Data\CategoryAttributeInterface::ENTITY_TYPE_CODE, $key)
                    ) {
                        $data[$key] = $value;
                    }
                } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
                    $this->output->writeln('* Attribute "' . $key . '" does not exist');
                    $this->log->addError($e);
                }
            }
        }

        try {
            if (isset($row['store_id'])) {
                $this->storeManager->getStore()->setId($row['store_id']);
            }

            $category->addData($data);

            $this->categoryRepository->save($category);
            $this->output->writeln('SAVED for store  ' . $category->getStoreId());
        } catch (\Exception $e) {
            $this->output->writeln('*** ERROR ***');
        }
    }

    /**
     * @param array $row
     *
     * @return \Magento\Framework\DataObject
     * @throws \Exception
     */
    protected function getParentCategory($row)
    {
        if (isset($row['parent_name']) && isset($row['level'])) {
            $parentCategory = $this->loadCategoryByName($row['parent_name'], $row['level'] - 1);
        } else {
            throw new \Exception('Parent category not defined properly (must have parent_name/level keys defined)');
        }

        return $parentCategory;
    }

    /**
     * @param string $name
     * @param string $level
     *
     * @return \Magento\Framework\DataObject
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function loadCategoryByName($name, $level)
    {
        /** @var \Magento\Catalog\Model\ResourceModel\Category\Collection $categoryCollection */
        $categoryCollection = $this->categoryCollectionFactory->create();

        $categoryCollection
            ->addAttributeToFilter('name', $name)
            ->addAttributeToFilter('level', $level);

        return $categoryCollection->getFirstItem();
    }

    /**
     * @param string $file
     *
     * @return array
     * @throws \Exception
     */
    protected function getCsvData($file)
    {
        $this->csv->setDelimiter(',');
        $this->csv->setEnclosure('"');

        return $this->csv->getData($file);
    }
}