<?php
namespace Magento\Sales\Model\Order\InvoiceRepository;

/**
 * Interceptor class for @see \Magento\Sales\Model\Order\InvoiceRepository
 */
class Interceptor extends \Magento\Sales\Model\Order\InvoiceRepository implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Sales\Model\ResourceModel\Metadata $invoiceMetadata, \Magento\Sales\Api\Data\InvoiceSearchResultInterfaceFactory $searchResultFactory)
    {
        $this->___init();
        parent::__construct($invoiceMetadata, $searchResultFactory);
    }

    /**
     * {@inheritdoc}
     */
    public function save(\Magento\Sales\Api\Data\InvoiceInterface $entity)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'save');
        if (!$pluginInfo) {
            return parent::save($entity);
        } else {
            return $this->___callPlugins('save', func_get_args(), $pluginInfo);
        }
    }
}
