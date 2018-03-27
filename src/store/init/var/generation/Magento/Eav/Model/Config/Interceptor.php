<?php
namespace Magento\Eav\Model\Config;

/**
 * Interceptor class for @see \Magento\Eav\Model\Config
 */
class Interceptor extends \Magento\Eav\Model\Config implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\CacheInterface $cache, \Magento\Eav\Model\Entity\TypeFactory $entityTypeFactory, \Magento\Eav\Model\ResourceModel\Entity\Type\CollectionFactory $entityTypeCollectionFactory, \Magento\Framework\App\Cache\StateInterface $cacheState, \Magento\Framework\Validator\UniversalFactory $universalFactory)
    {
        $this->___init();
        parent::__construct($cache, $entityTypeFactory, $entityTypeCollectionFactory, $cacheState, $universalFactory);
    }

    /**
     * {@inheritdoc}
     */
    public function getAttribute($entityType, $code)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getAttribute');
        if (!$pluginInfo) {
            return parent::getAttribute($entityType, $code);
        } else {
            return $this->___callPlugins('getAttribute', func_get_args(), $pluginInfo);
        }
    }
}
