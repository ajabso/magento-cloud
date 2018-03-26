<?php
namespace Magento\Framework\Escaper;

/**
 * Interceptor class for @see \Magento\Framework\Escaper
 */
class Interceptor extends \Magento\Framework\Escaper implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct($encoding = null)
    {
        $this->___init();
        parent::__construct($encoding);
    }

    /**
     * {@inheritdoc}
     */
    public function escapeHtml($data, $allowedTags = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'escapeHtml');
        if (!$pluginInfo) {
            return parent::escapeHtml($data, $allowedTags);
        } else {
            return $this->___callPlugins('escapeHtml', func_get_args(), $pluginInfo);
        }
    }
}
