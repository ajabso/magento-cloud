<?php
namespace Birks\Sales\Controller\Adminhtml\Order\ExportERP;

/**
 * Interceptor class for @see \Birks\Sales\Controller\Adminhtml\Order\ExportERP
 */
class Interceptor extends \Birks\Sales\Controller\Adminhtml\Order\ExportERP implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Birks\Sales\Model\AdminOrder\Export $export)
    {
        $this->___init();
        parent::__construct($context, $export);
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
