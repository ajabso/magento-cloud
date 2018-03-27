<?php
namespace Birks\Catalog\Controller\Product\View;

/**
 * Interceptor class for @see \Birks\Catalog\Controller\Product\View
 */
class Interceptor extends \Birks\Catalog\Controller\Product\View implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Catalog\Helper\Product\View $viewHelper, \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory, \Magento\Catalog\Model\ProductRepository $productRepository, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Birks\Catalog\Helper\Data $helper)
    {
        $this->___init();
        parent::__construct($context, $viewHelper, $resultForwardFactory, $productRepository, $resultPageFactory, $helper);
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
