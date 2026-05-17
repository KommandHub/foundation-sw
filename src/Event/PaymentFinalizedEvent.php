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
 * @package Kommandhub\Foundation\Event
 */
abstract class PaymentFinalizedEvent implements ShopwareEvent
{
    abstract public function getContext(): Context;

    abstract public function getOrder(): OrderEntity;

    abstract public function getOrderTransaction(): OrderTransactionEntity;

    abstract public function getPaymentTransactionStruct(): PaymentTransactionStruct;
}