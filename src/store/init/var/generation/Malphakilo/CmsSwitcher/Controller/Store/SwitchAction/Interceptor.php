<?php
namespace Malphakilo\CmsSwitcher\Controller\Store\SwitchAction;

/**
 * Interceptor class for @see \Malphakilo\CmsSwitcher\Controller\Store\SwitchAction
 */
class Interceptor extends \Malphakilo\CmsSwitcher\Controller\Store\SwitchAction implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Store\Api\StoreCookieManagerInterface $storeCookieManager, \Magento\Framework\App\Http\Context $httpContext, \Magento\Store\Api\StoreRepositoryInterface $storeRepository, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Cms\Api\Data\PageInterfaceFactory $pageFactory)
    {
        $this->___init();
        parent::__construct($context, $storeCookieManager, $httpContext, $storeRepository, $storeManager, $pageFactory);
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'execute');
        if (!$pluginInfo) {
            return parent::execute();
        } else {
            return $this->___callPlugins('execute', func_get_args(), $pluginInfo);
        }
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
