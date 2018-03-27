<?php
namespace Amasty\Shopby\Block\Navigation\State;

/**
 * Interceptor class for @see \Amasty\Shopby\Block\Navigation\State
 */
class Interceptor extends \Amasty\Shopby\Block\Navigation\State implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\View\Element\Template\Context $context, \Magento\Catalog\Model\Layer\Resolver $layerResolver, \Amasty\Shopby\Helper\FilterSetting $filterSettingHelper, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $layerResolver, $filterSettingHelper, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getClearUrl()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getClearUrl');
        if (!$pluginInfo) {
            return parent::getClearUrl();
        } else {
            return $this->___callPlugins('getClearUrl', func_get_args(), $pluginInfo);
        }
    }
}
