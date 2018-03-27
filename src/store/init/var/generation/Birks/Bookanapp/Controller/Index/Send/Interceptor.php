<?php
namespace Birks\Bookanapp\Controller\Index\Send;

/**
 * Interceptor class for @see \Birks\Bookanapp\Controller\Index\Send
 */
class Interceptor extends \Birks\Bookanapp\Controller\Index\Send implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder, \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Framework\Controller\Result\JsonFactory $jsonFactory, \Magento\Catalog\Model\Session $catalogSession, \Magento\Framework\Registry $registry, \Birks\Storelocator\Model\StoreFactory $storelocatorFactory, \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager, \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory $cookieMetadataFactory, \Magento\Framework\Session\SessionManagerInterface $sessionManager, \Magento\Framework\App\Request\DataPersistorInterface $dataPersistorInterface, \Magento\Catalog\Model\ProductRepository $productRepository)
    {
        $this->___init();
        parent::__construct($context, $transportBuilder, $inlineTranslation, $scopeConfig, $storeManager, $jsonFactory, $catalogSession, $registry, $storelocatorFactory, $cookieManager, $cookieMetadataFactory, $sessionManager, $dataPersistorInterface, $productRepository);
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
