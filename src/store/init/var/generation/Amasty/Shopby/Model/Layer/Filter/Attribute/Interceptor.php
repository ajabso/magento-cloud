<?php
namespace Amasty\Shopby\Model\Layer\Filter\Attribute;

/**
 * Interceptor class for @see \Amasty\Shopby\Model\Layer\Filter\Attribute
 */
class Interceptor extends \Amasty\Shopby\Model\Layer\Filter\Attribute implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Catalog\Model\Layer\Filter\ItemFactory $filterItemFactory, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Catalog\Model\Layer $layer, \Magento\Catalog\Model\Layer\Filter\Item\DataBuilder $itemDataBuilder, \Magento\Framework\Filter\StripTags $tagFilter, \Amasty\Shopby\Model\Search\Adapter\Mysql\AggregationAdapter $aggregationAdapter, array $data, \Amasty\Shopby\Helper\FilterSetting $settingHelper, \Amasty\Shopby\Model\Request $shopbyRequest, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig)
    {
        $this->___init();
        parent::__construct($filterItemFactory, $storeManager, $layer, $itemDataBuilder, $tagFilter, $aggregationAdapter, $data, $settingHelper, $shopbyRequest, $scopeConfig);
    }

    /**
     * {@inheritdoc}
     */
    public function shouldAddState()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'shouldAddState');
        if (!$pluginInfo) {
            return parent::shouldAddState();
        } else {
            return $this->___callPlugins('shouldAddState', func_get_args(), $pluginInfo);
        }
    }
}
