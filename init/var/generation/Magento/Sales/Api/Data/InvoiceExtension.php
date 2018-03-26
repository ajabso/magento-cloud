<?php
namespace Magento\Sales\Api\Data;

/**
 * Extension class for @see \Magento\Sales\Api\Data\InvoiceInterface
 */
class InvoiceExtension extends \Magento\Framework\Api\AbstractSimpleObject implements \Magento\Sales\Api\Data\InvoiceExtensionInterface
{
    /**
     * @return float|null
     */
    public function getBaseCustomerBalanceAmount()
    {
        return $this->_get('base_customer_balance_amount');
    }

    /**
     * @param float $baseCustomerBalanceAmount
     * @return $this
     */
    public function setBaseCustomerBalanceAmount($baseCustomerBalanceAmount)
    {
        $this->setData('base_customer_balance_amount', $baseCustomerBalanceAmount);
        return $this;
    }

    /**
     * @return float|null
     */
    public function getCustomerBalanceAmount()
    {
        return $this->_get('customer_balance_amount');
    }

    /**
     * @param float $customerBalanceAmount
     * @return $this
     */
    public function setCustomerBalanceAmount($customerBalanceAmount)
    {
        $this->setData('customer_balance_amount', $customerBalanceAmount);
        return $this;
    }

    /**
     * @return float|null
     */
    public function getBaseGiftCardsAmount()
    {
        return $this->_get('base_gift_cards_amount');
    }

    /**
     * @param float $baseGiftCardsAmount
     * @return $this
     */
    public function setBaseGiftCardsAmount($baseGiftCardsAmount)
    {
        $this->setData('base_gift_cards_amount', $baseGiftCardsAmount);
        return $this;
    }

    /**
     * @return float|null
     */
    public function getGiftCardsAmount()
    {
        return $this->_get('gift_cards_amount');
    }

    /**
     * @param float $giftCardsAmount
     * @return $this
     */
    public function setGiftCardsAmount($giftCardsAmount)
    {
        $this->setData('gift_cards_amount', $giftCardsAmount);
        return $this;
    }
}
