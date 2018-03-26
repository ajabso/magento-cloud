<?php
namespace Amasty\Shopby\Block\Navigation\FilterRenderer;

/**
 * Interceptor class for @see \Amasty\Shopby\Block\Navigation\FilterRenderer
 */
class Interceptor extends \Amasty\Shopby\Block\Navigation\FilterRenderer implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\View\Element\Template\Context $context, \Amasty\Shopby\Helper\FilterSetting $settingHelper, \Amasty\Shopby\Helper\UrlBuilder $urlBuilder, \Amasty\Shopby\Helper\Data $helper, \Amasty\Shopby\Helper\Category $categoryHelper, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $settingHelper, $urlBuilder, $helper, $categoryHelper, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function render(\Magento\Catalog\Model\Layer\Filter\FilterInterface $filter)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'render');
        if (!$pluginInfo) {
            return parent::render($filter);
        } else {
            return $this->___callPlugins('render', func_get_args(), $pluginInfo);
        }
    }
}
