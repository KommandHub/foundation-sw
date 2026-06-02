<?php

declare(strict_types=1);

namespace Kommandhub\Foundation\Tests\Fixture;

use Kommandhub\Foundation\Tests\Fixture\Helper\Order\DeliveryFixtureDefinition;
use Kommandhub\Foundation\Tests\Fixture\Helper\Order\OrderAddressFixtureDefinition;
use Kommandhub\Foundation\Tests\Fixture\Helper\Order\OrderFixtureDefinition;
use Kommandhub\Foundation\Tests\Fixture\Helper\Order\OrderFixtureLoader;
use Kommandhub\Foundation\Tests\Fixture\Helper\Order\TransactionFixtureDefinition;
use Shopware\Core\Checkout\Order\Aggregate\OrderDelivery\OrderDeliveryStates;
use Shopware\Core\Checkout\Order\Aggregate\OrderTransaction\OrderTransactionStates;
use Shopware\Core\Checkout\Order\OrderStates;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\SalesChannel\SalesChannelEntity;
use Shopware\FixtureBundle\Attribute\Fixture;
use Shopware\FixtureBundle\FixtureInterface;
use Shopware\FixtureBundle\Helper\Customer\CustomerFixtureDefinition;

#[Fixture(dependsOn: [CustomerFixture::class, CountryFixture::class], groups: ['foundation'])]
class OrderFixture implements FixtureInterface
{
    public const ORDER_ID_1 = '018f9f688e1e72e18995a00446e5e011';
    public const ORDER_NUMBER_1 = '10001';

    public const ORDER_ID_2 = '018f9f688e1e72e18995a00446e5e012';
    public const ORDER_NUMBER_2 = '10002';

    public const ORDER_ID_3 = '018f9f688e1e72e18995a00446e5e013';
    public const ORDER_NUMBER_3 = '10003';

    public function __construct(
        private readonly OrderFixtureLoader $orderFixtureLoader
    ) {
    }

    public function load(): void
    {
        $context = Context::createDefaultContext();
        $salesChannel = $this->orderFixtureLoader->getSalesChannel($context);

        $orders = [
            [
                'id' => self::ORDER_ID_1,
                'number' => self::ORDER_NUMBER_1,
            ],
            [
                'id' => self::ORDER_ID_2,
                'number' => self::ORDER_NUMBER_2,
            ],
            [
                'id' => self::ORDER_ID_3,
                'number' => self::ORDER_NUMBER_3,
            ],
        ];

        foreach ($orders as $orderData) {
            $order = (new OrderFixtureDefinition($orderData['number']))
                ->id($orderData['id'])
                ->salesChannelId($salesChannel->getId())
                ->stateId(
                    $this->orderFixtureLoader->getInitialStateId(OrderStates::STATE_MACHINE)
                )
                ->salutationId(
                    $this->orderFixtureLoader->getDefaultSalutationId($context)
                );

            $order->orderCustomer(
                (new CustomerFixtureDefinition('customer@example.com'))
                    ->firstName('John')
                    ->lastName('Doe')
            );

            $order->billingAddress(
                (new OrderAddressFixtureDefinition())
                    ->firstName('John')
                    ->lastName('Doe')
                    ->street('Main St 1')
                    ->zipcode('12345')
                    ->city('City')
            );

            $order->addDelivery(
                $this->buildDelivery($salesChannel)
            );

            $order->addTransaction(
                $this->buildTransaction($salesChannel)
            );

            $this->orderFixtureLoader->apply($order);
        }
    }

    private function buildDelivery(SalesChannelEntity $salesChannel): DeliveryFixtureDefinition
    {
        $shippingMethodId = $salesChannel->getShippingMethodId();

        if (!Uuid::isValid((string)$shippingMethodId)) {
            throw new \RuntimeException('Sales channel shipping method not found.');
        }

        return (new DeliveryFixtureDefinition())
            ->shippingMethodId($shippingMethodId)
            ->stateId(
                $this->orderFixtureLoader->getInitialStateId(
                    OrderDeliveryStates::STATE_MACHINE
                )
            );
    }

    private function buildTransaction(SalesChannelEntity $salesChannel): TransactionFixtureDefinition
    {
        $paymentMethodId = $salesChannel->getPaymentMethodId();

        if (!Uuid::isValid($paymentMethodId)) {
            throw new \RuntimeException('Sales channel payment method not found.');
        }

        return (new TransactionFixtureDefinition())
            ->paymentMethodId($paymentMethodId)
            ->stateId(
                $this->orderFixtureLoader->getInitialStateId(
                    OrderTransactionStates::STATE_MACHINE
                )
            );
    }
}
