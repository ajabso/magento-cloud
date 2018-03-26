<?php
namespace Amasty\ShopbyBrand\Controller\Adminhtml\Slider\Edit;

/**
 * Interceptor class for @see \Amasty\ShopbyBrand\Controller\Adminhtml\Slider\Edit
 */
class Interceptor extends \Amasty\ShopbyBrand\Controller\Adminhtml\Slider\Edit implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Magento\Framework\Registry $registry, \Amasty\Shopby\Helper\OptionSetting $optionSetting)
    {
        $this->___init();
        parent::__construct($context, $resultPageFactory, $registry, $optionSetting);
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
