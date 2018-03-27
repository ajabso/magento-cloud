<?php
namespace Absolunet\Brand\Controller\Adminhtml\Brand\Grid;

/**
 * Interceptor class for @see \Absolunet\Brand\Controller\Adminhtml\Brand\Grid
 */
class Interceptor extends \Absolunet\Brand\Controller\Adminhtml\Brand\Grid implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\View\Result\PageFactory $resultPageFactory, \Magento\Framework\Registry $registry, \Absolunet\Brand\Model\BrandFactory $brandFactory, \Magento\Backend\App\Action\Context $context, \Magento\Framework\Controller\Result\RawFactory $resultRawFactory, \Magento\Framework\View\LayoutFactory $layoutFactory, \Magento\Store\Model\StoreManagerInterface $storeManagerInterface)
    {
        $this->___init();
        parent::__construct($resultPageFactory, $registry, $brandFactory, $context, $resultRawFactory, $layoutFactory, $storeManagerInterface);
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
