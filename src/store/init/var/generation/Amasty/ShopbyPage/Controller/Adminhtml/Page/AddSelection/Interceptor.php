<?php
namespace Amasty\ShopbyPage\Controller\Adminhtml\Page\AddSelection;

/**
 * Interceptor class for @see \Amasty\ShopbyPage\Controller\Adminhtml\Page\AddSelection
 */
class Interceptor extends \Amasty\ShopbyPage\Controller\Adminhtml\Page\AddSelection implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory, \Magento\Catalog\Model\Config $catalogConfig, \Magento\Framework\Data\FormFactory $formFactory, \Amasty\ShopbyPage\Block\Adminhtml\Page\Edit\Tab\SelectionFactory $tabSelectionFactory, \Amasty\ShopbyPage\Model\Config\Source\Attribute $sourceAttribute)
    {
        $this->___init();
        parent::__construct($context, $resultJsonFactory, $catalogConfig, $formFactory, $tabSelectionFactory, $sourceAttribute);
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
