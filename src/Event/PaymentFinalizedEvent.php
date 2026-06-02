<?php

declare(strict_types=1);

namespace Kommandhub\Foundation\Event;

use Shopware\Core\Checkout\Order\Aggregate\OrderTransaction\OrderTransactionEntity;
use Shopware\Core\Checkout\Order\OrderEntity;
use Shopware\Core\Checkout\Payment\Cart\PaymentTransactionStruct;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\Event\ShopwareEvent;

/**
 * Represents an event triggered when a payment is finalized in the Shopware system.
 * This abstract class serves as the base for handling finalized payment events.
 *
 * Provides access to the context, order details, order transaction, and payment transaction struct.
 */
abstract class PaymentFinalizedEvent implements ShopwareEvent
{
    public function __construct(
        protected OrderEntity $order,
        protected OrderTransactionEntity $orderTransaction,
        protected PaymentTransactionStruct $paymentTransactionStruct,
        protected Context $context,
    ) {
    }

    public function getContext(): Context
    {
        return $this->context;
    }

    public function getOrder(): OrderEntity
    {
        return $this->order;
    }

    public function getOrderTransaction(): OrderTransactionEntity
    {
        return $this->orderTransaction;
    }

    public function getPaymentTransactionStruct(): PaymentTransactionStruct
    {
        return $this->paymentTransactionStruct;
    }
}
