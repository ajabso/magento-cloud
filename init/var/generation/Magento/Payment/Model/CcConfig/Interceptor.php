<?php
namespace Magento\Payment\Model\CcConfig;

/**
 * Interceptor class for @see \Magento\Payment\Model\CcConfig
 */
class Interceptor extends \Magento\Payment\Model\CcConfig implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Payment\Model\Config $paymentConfig, \Magento\Framework\View\Asset\Repository $assetRepo, \Magento\Framework\App\RequestInterface $request, \Magento\Framework\UrlInterface $urlBuilder, \Psr\Log\LoggerInterface $logger)
    {
        $this->___init();
        parent::__construct($paymentConfig, $assetRepo, $request, $urlBuilder, $logger);
    }

    /**
     * {@inheritdoc}
     */
    public function getCvvImageUrl()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCvvImageUrl');
        if (!$pluginInfo) {
            return parent::getCvvImageUrl();
        } else {
            return $this->___callPlugins('getCvvImageUrl', func_get_args(), $pluginInfo);
        }
    }
}
