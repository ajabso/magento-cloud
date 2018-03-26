<?php
namespace Magestore\Storelocator\Controller\Index\View;

/**
 * Interceptor class for @see \Magestore\Storelocator\Controller\Index\View
 */
class Interceptor extends \Magestore\Storelocator\Controller\Index\View implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magestore\Storelocator\Model\SystemConfig $systemConfig, \Magestore\Storelocator\Model\ResourceModel\Store\CollectionFactory $storeCollectionFactory, \Magento\Framework\Registry $coreRegistry, \Magento\Framework\Json\Helper\Data $jsonHelper)
    {
        $this->___init();
        parent::__construct($context, $systemConfig, $storeCollectionFactory, $coreRegistry, $jsonHelper);
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
