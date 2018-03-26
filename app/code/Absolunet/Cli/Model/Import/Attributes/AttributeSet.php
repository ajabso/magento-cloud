<?php
/**
 * @author    Mackendy Jeudy <mjeudy@absolunet.com>
 * @author    Cyril Ekoule <cekoule@absolunet.com>
 * @author    Mathieu Gervais <mgervais@absolunet.com>
 * @copyright Copyright (c) 2016 Absolunet (http://www.absolunet.com)
 * @link      http://www.absolunet.com
 */

namespace Absolunet\Cli\Model\Import\Attributes;

use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\File\Csv as Csv;
use Magento\Eav\Model\Entity\Type;
use Magento\Catalog\Model\Product\AttributeSet\Build;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;

class AttributeSet extends Build
{
    /** @var Type */
    protected $type;

    /** @var Csv */
    protected $csv;

    /**
     * AttributeSet constructor.
     * @param AttributeSetFactory $attributeSetFactory
     * @param Type $type
     * @param Csv $csv
     */
    public function __construct(
        AttributeSetFactory $attributeSetFactory,
        Type $type,
        Csv $csv
    ) {
    
        $this->type = $type;
        $this->csv  = $csv;

        parent::__construct($attributeSetFactory);
    }

    /**
     * @param string $name
     * @param string $entityTypeCode
     *
     * @return \Magento\Eav\Model\Entity\Attribute\Set
     * @throws AlreadyExistsException
     */
    public function create($name, $entityTypeCode)
    {
        $entityType            = $this->type->loadByCode($entityTypeCode);
        $defaultAttributeSetId = $entityType->getDefaultAttributeSetId();

        $this->setName($name)
             ->setEntityTypeId($entityType->getId())
             ->setSkeletonId($defaultAttributeSetId);

        return $this->getAttributeSet();
    }

    /**
     * @param $filePath
     * @return array
     * @throws \Exception
     */
    public function readFile($filePath)
    {
        $data              = $this->getCsvData($filePath);
        $attributeSetNames = $this->validateData($data);

        return $attributeSetNames;
    }

    /**
     * @param $file
     * @return array
     */
    protected function getCsvData($file)
    {
        $this->csv->setDelimiter(',');
        $this->csv->setEnclosure('"');

        return $this->csv->getData($file);
    }

    /**
     * @param $data
     * @return array
     * @throws \Exception
     */
    protected function validateData($data)
    {
        $keys = array();
        $attributeSetNames = array();

        foreach ($data as $rowId => $row) {
            // header
            if ($rowId == 0) {
                $keys = array_values($row);

                if (!empty($keys) && $keys[0] != 'attribute_set_name') {
                    throw new \Exception('The "attribute_set_name" column is missing');
                }

                continue;
            }

            $values = array_values($row);

            if (count($keys) != count($values)) {
                $line = $rowId + 1;
                throw new \Exception("Number of keys does not match number of values at line {$line}");
            }

            $attributeSetNames[] = $row[0];
        }

        return $attributeSetNames;
    }
}
