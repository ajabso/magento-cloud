<?php
namespace Absolunet\Brand\Block\Category\View;

/**
 * Interceptor class for @see \Absolunet\Brand\Block\Category\View
 */
class Interceptor extends \Absolunet\Brand\Block\Category\View implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\View\Element\Template\Context $context, \Magento\Catalog\Model\Layer\Resolver $layerResolver, \Magento\Framework\Registry $registry, \Magento\Catalog\Helper\Category $categoryHelper, \Absolunet\Brand\Model\BrandFactory $brandFactory, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $layerResolver, $registry, $categoryHelper, $brandFactory, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrentCategory()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCurrentCategory');
        if (!$pluginInfo) {
            return parent::getCurrentCategory();
        } else {
            return $this->___callPlugins('getCurrentCategory', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isMixedMode()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isMixedMode');
        if (!$pluginInfo) {
            return parent::isMixedMode();
        } else {
            return $this->___callPlugins('isMixedMode', func_get_args(), $pluginInfo);
        }
    }
}
