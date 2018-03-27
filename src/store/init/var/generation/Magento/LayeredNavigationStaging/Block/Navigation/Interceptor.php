<?php
namespace Magento\LayeredNavigationStaging\Block\Navigation;

/**
 * Interceptor class for @see \Magento\LayeredNavigationStaging\Block\Navigation
 */
class Interceptor extends \Magento\LayeredNavigationStaging\Block\Navigation implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\View\Element\Template\Context $context, \Magento\Catalog\Model\Layer\Resolver $layerResolver, \Magento\Catalog\Model\Layer\FilterList $filterList, \Magento\Catalog\Model\Layer\AvailabilityFlagInterface $visibilityFlag, \Magento\Staging\Model\VersionManager $versionManager, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $layerResolver, $filterList, $visibilityFlag, $versionManager, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function toHtml()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'toHtml');
        if (!$pluginInfo) {
            return parent::toHtml();
        } else {
            return $this->___callPlugins('toHtml', func_get_args(), $pluginInfo);
        }
    }
}
