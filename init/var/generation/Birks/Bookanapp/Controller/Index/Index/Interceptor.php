<?php
namespace Birks\Bookanapp\Controller\Index\Index;

/**
 * Interceptor class for @see \Birks\Bookanapp\Controller\Index\Index
 */
class Interceptor extends \Birks\Bookanapp\Controller\Index\Index implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager, \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory $cookieMetadataFactory, \Magento\Framework\Session\SessionManagerInterface $sessionManager)
    {
        $this->___init();
        parent::__construct($context, $cookieManager, $cookieMetadataFactory, $sessionManager);
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
