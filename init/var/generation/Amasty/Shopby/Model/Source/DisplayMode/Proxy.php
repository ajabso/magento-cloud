<?php
namespace Amasty\Shopby\Model\Source\DisplayMode;

/**
 * Proxy class for @see \Amasty\Shopby\Model\Source\DisplayMode
 */
class Proxy extends \Amasty\Shopby\Model\Source\DisplayMode implements \Magento\Framework\ObjectManager\NoninterceptableInterface
{
    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager = null;

    /**
     * Proxied instance name
     *
     * @var string
     */
    protected $_instanceName = null;

    /**
     * Proxied instance
     *
     * @var \Amasty\Shopby\Model\Source\DisplayMode
     */
    protected $_subject = null;

    /**
     * Instance shareability flag
     *
     * @var bool
     */
    protected $_isShared = null;

    /**
     * Proxy constructor
     *
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param string $instanceName
     * @param bool $shared
     */
    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager, $instanceName = '\\Amasty\\Shopby\\Model\\Source\\DisplayMode', $shared = true)
    {
        $this->_objectManager = $objectManager;
        $this->_instanceName = $instanceName;
        $this->_isShared = $shared;
    }

    /**
     * @return array
     */
    public function __sleep()
    {
        return ['_subject', '_isShared', '_instanceName'];
    }

    /**
     * Retrieve ObjectManager from global scope
     */
    public function __wakeup()
    {
        $this->_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    }

    /**
     * Clone proxied instance
     */
    public function __clone()
    {
        $this->_subject = clone $this->_getSubject();
    }

    /**
     * Get proxied instance
     *
     * @return \Amasty\Shopby\Model\Source\DisplayMode
     */
    protected function _getSubject()
    {
        if (!$this->_subject) {
            $this->_subject = true === $this->_isShared
                ? $this->_objectManager->get($this->_instanceName)
                : $this->_objectManager->create($this->_instanceName);
        }
        return $this->_subject;
    }

    /**
     * {@inheritdoc}
     */
    public function setAttributeType($attributeType)
    {
        return $this->_getSubject()->setAttributeType($attributeType);
    }

    /**
     * {@inheritdoc}
     */
    public function setAttribute(\Magento\Catalog\Model\ResourceModel\Eav\Attribute $attribute)
    {
        return $this->_getSubject()->setAttribute($attribute);
    }

    /**
     * {@inheritdoc}
     */
    public function showSwatchOptions()
    {
        return $this->_getSubject()->showSwatchOptions();
    }

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        return $this->_getSubject()->toOptionArray();
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return $this->_getSubject()->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function getInputTypeMap()
    {
        return $this->_getSubject()->getInputTypeMap();
    }
}
