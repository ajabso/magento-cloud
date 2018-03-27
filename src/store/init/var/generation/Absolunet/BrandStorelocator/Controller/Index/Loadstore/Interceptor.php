<?php
namespace Absolunet\BrandStorelocator\Controller\Index\Loadstore;

/**
 * Interceptor class for @see \Absolunet\BrandStorelocator\Controller\Index\Loadstore
 */
class Interceptor extends \Absolunet\BrandStorelocator\Controller\Index\Loadstore implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magestore\Storelocator\Model\SystemConfig $systemConfig, \Magestore\Storelocator\Model\ResourceModel\Store\CollectionFactory $storeCollectionFactory, \Magento\Framework\Registry $coreRegistry, \Magento\Framework\Json\Helper\Data $jsonHelper, \Absolunet\BrandStorelocator\Model\ResourceModel\Store\CollectionFactory $brandStoreCollectionFactory, \Magento\Framework\Session\SessionManager $session)
    {
        $this->___init();
        parent::__construct($context, $systemConfig, $storeCollectionFactory, $coreRegistry, $jsonHelper, $brandStoreCollectionFactory, $session);
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
