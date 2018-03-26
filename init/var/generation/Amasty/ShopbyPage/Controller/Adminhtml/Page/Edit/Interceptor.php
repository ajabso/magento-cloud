<?php
namespace Amasty\ShopbyPage\Controller\Adminhtml\Page\Edit;

/**
 * Interceptor class for @see \Amasty\ShopbyPage\Controller\Adminhtml\Page\Edit
 */
class Interceptor extends \Amasty\ShopbyPage\Controller\Adminhtml\Page\Edit implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Magento\Framework\Registry $registry, \Amasty\ShopbyPage\Api\Data\PageInterfaceFactory $pageFactory, \Amasty\ShopbyPage\Api\PageRepositoryInterface $pageRepository)
    {
        $this->___init();
        parent::__construct($context, $resultPageFactory, $registry, $pageFactory, $pageRepository);
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
