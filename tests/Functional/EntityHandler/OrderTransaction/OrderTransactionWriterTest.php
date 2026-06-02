<?php

declare(strict_types=1);

namespace Kommandhub\Foundation\Tests\Functional\EntityHandler\OrderTransaction;

use Kommandhub\Foundation\EntityHandler\OrderTransaction\OrderTransactionWriter;
use Kommandhub\Foundation\Fixture\OrderFixture;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Checkout\Order\Aggregate\OrderTransaction\OrderTransactionStates;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\Test\TestCaseBase\IntegrationTestBehaviour;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\StateMachine\Loader\InitialStateIdLoader;

class OrderTransactionWriterTest extends TestCase
{
    use IntegrationTestBehaviour;

    private OrderTransactionWriter $writer;
    private Context $context;
    private EntityRepository $orderTransactionRepository;

    protected function setUp(): void
    {
        $this->orderTransactionRepository = $this->getContainer()->get('order_transaction.repository');
        $this->writer = new OrderTransactionWriter($this->orderTransactionRepository);
        $this->context = Context::createDefaultContext();
    }

    public function testWrite(): void
    {
        $orderId = OrderFixture::ORDER_ID_1;
        $this->ensureOrderExists($orderId);

        $paymentMethodId = $this->getValidPaymentMethodId();
        $stateId = $this->getContainer()->get(InitialStateIdLoader::class)->get(OrderTransactionStates::STATE_MACHINE);

        $transactionId = Uuid::randomHex();
        $data = [
            'id' => $transactionId,
            'orderId' => $orderId,
            'paymentMethodId' => $paymentMethodId,
            'stateId' => $stateId,
            'amount' => [
                'unitPrice' => 100.0,
                'totalPrice' => 100.0,
                'quantity' => 1,
                'calculatedTaxes' => [],
                'taxRules' => [],
            ],
        ];

        $this->writer->write($data, $this->context);

        $exists = $this->orderTransactionRepository->searchIds(new Criteria([$transactionId]), $this->context)->has($transactionId);
        $this->assertTrue($exists);
    }

    public function testWriteMany(): void
    {
        $orderId = OrderFixture::ORDER_ID_1;
        $this->ensureOrderExists($orderId);

        $paymentMethodId = $this->getValidPaymentMethodId();
        $stateId = $this->getContainer()->get(InitialStateIdLoader::class)->get(OrderTransactionStates::STATE_MACHINE);

        $ids = [Uuid::randomHex(), Uuid::randomHex()];
        $rows = [];

        foreach ($ids as $id) {
            $rows[] = [
                'id' => $id,
                'orderId' => $orderId,
                'paymentMethodId' => $paymentMethodId,
                'stateId' => $stateId,
                'amount' => [
                    'unitPrice' => 50.0,
                    'totalPrice' => 50.0,
                    'quantity' => 1,
                    'calculatedTaxes' => [],
                    'taxRules' => [],
                ],
            ];
        }

        $writtenIds = $this->writer->writeMany($rows, $this->context);
        $this->assertEquals($ids, $writtenIds);

        $result = $this->orderTransactionRepository->searchIds(new Criteria($ids), $this->context);
        $this->assertCount(2, $result->getIds());
    }

    private function ensureOrderExists(string $orderId): void
    {
        $orderRepo = $this->getContainer()->get('order.repository');
        $exists = $orderRepo->searchIds(new Criteria([$orderId]), $this->context)->firstId();

        if ($exists) {
            return;
        }

        $salutationId = $this->getValidSalutationId();
        $orderRepo->create([
            [
                'id' => $orderId,
                'orderNumber' => Uuid::randomHex(),
                'orderDateTime' => (new \DateTime())->format('Y-m-d H:i:s'),
                'price' => [
                    'netPrice' => 100.0,
                    'totalPrice' => 100.0,
                    'positionPrice' => 100.0,
                    'rawTotal' => 100.0,
                    'taxStatus' => 'gross',
                    'calculatedTaxes' => [],
                    'taxRules' => [],
                ],
                'shippingCosts' => [
                    'unitPrice' => 0.0,
                    'totalPrice' => 0.0,
                    'quantity' => 1,
                    'calculatedTaxes' => [],
                    'taxRules' => [],
                ],
                'stateId' => $this->getContainer()->get(InitialStateIdLoader::class)->get('order.state'),
                'paymentMethodId' => $this->getValidPaymentMethodId(),
                'currencyId' => $this->context->getCurrencyId(),
                'currencyFactor' => 1.0,
                'salesChannelId' => \Shopware\Core\Test\TestDefaults::SALES_CHANNEL,
                'billingAddressId' => Uuid::randomHex(),
                'itemRounding' => [
                    'decimals' => 2,
                    'interval' => 0.01,
                    'roundForNet' => true,
                ],
                'totalRounding' => [
                    'decimals' => 2,
                    'interval' => 0.01,
                    'roundForNet' => true,
                ],
                'orderCustomer' => [
                    'email' => 'test@example.com',
                    'firstName' => 'Test',
                    'lastName' => 'Customer',
                    'salutationId' => $salutationId,
                    'customerNumber' => Uuid::randomHex(),
                ],
                'addresses' => [
                    [
                        'id' => Uuid::randomHex(),
                        'salutationId' => $salutationId,
                        'firstName' => 'Test',
                        'lastName' => 'Customer',
                        'street' => 'Street',
                        'zipcode' => '12345',
                        'city' => 'City',
                        'countryId' => $this->getValidCountryId(),
                    ],
                ],
            ],
        ], $this->context);
    }
}
