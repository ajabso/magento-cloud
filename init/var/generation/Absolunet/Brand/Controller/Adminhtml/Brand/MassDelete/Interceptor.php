<?php
namespace Absolunet\Brand\Controller\Adminhtml\Brand\MassDelete;

/**
 * Interceptor class for @see \Absolunet\Brand\Controller\Adminhtml\Brand\MassDelete
 */
class Interceptor extends \Absolunet\Brand\Controller\Adminhtml\Brand\MassDelete implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Ui\Component\MassAction\Filter $filter, \Absolunet\Brand\Model\ResourceModel\Brand\Collection $objectCollection, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Magento\Framework\Registry $registry, \Absolunet\Brand\Model\BrandFactory $brandFactory, \Magento\Store\Model\StoreManagerInterface $storeManagerInterface)
    {
        $this->___init();
        parent::__construct($context, $filter, $objectCollection, $resultPageFactory, $registry, $brandFactory, $storeManagerInterface);
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
