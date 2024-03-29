<?php
namespace Magento\MultipleWishlist\Controller\Index\Copyitems;

/**
 * Interceptor class for @see \Magento\MultipleWishlist\Controller\Index\Copyitems
 */
class Interceptor extends \Magento\MultipleWishlist\Controller\Index\Copyitems implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Wishlist\Controller\WishlistProviderInterface $wishlistProvider, \Magento\MultipleWishlist\Model\ItemManager $itemManager, \Magento\Wishlist\Model\ItemFactory $itemFactory, \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator)
    {
        $this->___init();
        parent::__construct($context, $wishlistProvider, $itemManager, $itemFactory, $formKeyValidator);
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
