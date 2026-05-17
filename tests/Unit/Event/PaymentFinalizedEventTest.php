<?php

declare(strict_types=1);

namespace Kommandhub\Foundation\Tests\Unit\Event;

use Kommandhub\Foundation\Event\PaymentFinalizedEvent;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Checkout\Order\Aggregate\OrderTransaction\OrderTransactionEntity;
use Shopware\Core\Checkout\Order\OrderEntity;
use Shopware\Core\Checkout\Payment\Cart\PaymentTransactionStruct;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\Event\ShopwareEvent;

class PaymentFinalizedEventTest extends TestCase
{
    public function testEventContract(): void
    {
        $context = $this->createMock(Context::class);
        $order = $this->createMock(OrderEntity::class);
        $orderTransaction = $this->createMock(OrderTransactionEntity::class);
        $paymentTransactionStruct = $this->createMock(PaymentTransactionStruct::class);

        /** @var PaymentFinalizedEvent $event */
        $event = $this->createMock(PaymentFinalizedEvent::class);

        $event->method('getContext')->willReturn($context);
        $event->method('getOrder')->willReturn($order);
        $event->method('getOrderTransaction')->willReturn($orderTransaction);
        $event->method('getPaymentTransactionStruct')->willReturn($paymentTransactionStruct);

        $this->assertInstanceOf(ShopwareEvent::class, $event);
        $this->assertSame($context, $event->getContext());
        $this->assertSame($order, $event->getOrder());
        $this->assertSame($orderTransaction, $event->getOrderTransaction());
        $this->assertSame($paymentTransactionStruct, $event->getPaymentTransactionStruct());
    }
}
