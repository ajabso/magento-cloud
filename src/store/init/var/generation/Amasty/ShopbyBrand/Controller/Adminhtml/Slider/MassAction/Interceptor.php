<?php
namespace Amasty\ShopbyBrand\Controller\Adminhtml\Slider\MassAction;

/**
 * Interceptor class for @see \Amasty\ShopbyBrand\Controller\Adminhtml\Slider\MassAction
 */
class Interceptor extends \Amasty\ShopbyBrand\Controller\Adminhtml\Slider\MassAction implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Ui\Component\MassAction\Filter $filter)
    {
        $this->___init();
        parent::__construct($context, $filter);
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(\Magento\Framework\App\RequestInterface $request)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'dispatch');
        if (!$pluginInfo) {
            return parent::dispatch($request);
        } else {
            return $this->___callPlugins('dispatch', func_get_args(), $pluginInfo);
        }
    }
}
