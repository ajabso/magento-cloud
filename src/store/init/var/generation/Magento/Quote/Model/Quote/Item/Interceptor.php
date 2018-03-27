<?php
namespace Magento\Quote\Model\Quote\Item;

/**
 * Interceptor class for @see \Magento\Quote\Model\Quote\Item
 */
class Interceptor extends \Magento\Quote\Model\Quote\Item implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Model\Context $context, \Magento\Framework\Registry $registry, \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory, \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory, \Magento\Catalog\Api\ProductRepositoryInterface $productRepository, \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency, \Magento\Sales\Model\Status\ListFactory $statusListFactory, \Magento\Framework\Locale\FormatInterface $localeFormat, \Magento\Quote\Model\Quote\Item\OptionFactory $itemOptionFactory, \Magento\Quote\Model\Quote\Item\Compare $quoteItemCompare, \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry, \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null, \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $registry, $extensionFactory, $customAttributeFactory, $productRepository, $priceCurrency, $statusListFactory, $localeFormat, $itemOptionFactory, $quoteItemCompare, $stockRegistry, $resource, $resourceCollection, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function representProduct($product)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'representProduct');
        if (!$pluginInfo) {
            return parent::representProduct($product);
        } else {
            return $this->___callPlugins('representProduct', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setPrice($price)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setPrice');
        if (!$pluginInfo) {
            return parent::setPrice($price);
        } else {
            return $this->___callPlugins('setPrice', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getMessage($string = true)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getMessage');
        if (!$pluginInfo) {
            return parent::getMessage($string);
        } else {
            return $this->___callPlugins('getMessage', func_get_args(), $pluginInfo);
        }
    }
}
