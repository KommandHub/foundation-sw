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
    /**
     * Returns the context of the event.
     *
     * @return Context
     */
    abstract public function getContext(): Context;

    /**
     * Returns the order associated with the event.
     *
     * @return OrderEntity
     */
    abstract public function getOrder(): OrderEntity;

    /**
     * Returns the order transaction associated with the event.
     *
     * @return OrderTransactionEntity
     */
    abstract public function getOrderTransaction(): OrderTransactionEntity;

    /**
     * Returns the payment transaction struct associated with the event.
     *
     * @return PaymentTransactionStruct
     */
    abstract public function getPaymentTransactionStruct(): PaymentTransactionStruct;
}
