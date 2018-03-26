<?php
namespace Birks\GoogleTagManager\Controller\CartValue\View;

/**
 * Interceptor class for @see \Birks\GoogleTagManager\Controller\CartValue\View
 */
class Interceptor extends \Birks\GoogleTagManager\Controller\CartValue\View implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory, \Magento\Checkout\Model\SessionFactory $checkoutSessionFactory)
    {
        $this->___init();
        parent::__construct($context, $resultJsonFactory, $checkoutSessionFactory);
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
