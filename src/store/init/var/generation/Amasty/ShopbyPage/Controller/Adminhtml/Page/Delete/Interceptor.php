<?php
namespace Amasty\ShopbyPage\Controller\Adminhtml\Page\Delete;

/**
 * Interceptor class for @see \Amasty\ShopbyPage\Controller\Adminhtml\Page\Delete
 */
class Interceptor extends \Amasty\ShopbyPage\Controller\Adminhtml\Page\Delete implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Amasty\ShopbyPage\Api\Data\PageInterfaceFactory $pageDataFactory, \Amasty\ShopbyPage\Api\PageRepositoryInterface $pageRepository)
    {
        $this->___init();
        parent::__construct($context, $pageDataFactory, $pageRepository);
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
