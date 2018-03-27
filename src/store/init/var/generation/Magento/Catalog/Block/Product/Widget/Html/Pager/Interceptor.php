<?php
namespace Magento\Catalog\Block\Product\Widget\Html\Pager;

/**
 * Interceptor class for @see \Magento\Catalog\Block\Product\Widget\Html\Pager
 */
class Interceptor extends \Magento\Catalog\Block\Product\Widget\Html\Pager implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\View\Element\Template\Context $context, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getPagerUrl($params = array())
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getPagerUrl');
        if (!$pluginInfo) {
            return parent::getPagerUrl($params);
        } else {
            return $this->___callPlugins('getPagerUrl', func_get_args(), $pluginInfo);
        }
    }
}
