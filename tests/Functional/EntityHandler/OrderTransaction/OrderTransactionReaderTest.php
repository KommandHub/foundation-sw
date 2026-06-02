<?php

declare(strict_types=1);

namespace Kommandhub\Foundation\Tests\Functional\EntityHandler\OrderTransaction;

use Kommandhub\Foundation\EntityHandler\OrderTransaction\OrderTransactionReader;
use Kommandhub\Foundation\Fixture\OrderFixture;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Checkout\Order\Aggregate\OrderTransaction\OrderTransactionCollection;
use Shopware\Core\Checkout\Order\Aggregate\OrderTransaction\OrderTransactionEntity;
use Shopware\Core\Checkout\Order\Aggregate\OrderTransaction\OrderTransactionStates;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\Test\TestCaseBase\IntegrationTestBehaviour;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\StateMachine\Loader\InitialStateIdLoader;

class OrderTransactionReaderTest extends TestCase
{
    use IntegrationTestBehaviour;

    private OrderTransactionReader $reader;
    private Context $context;
    private EntityRepository $orderTransactionRepository;

    protected function setUp(): void
    {
        $this->orderTransactionRepository = $this->getContainer()->get('order_transaction.repository');
        $this->reader = new OrderTransactionReader($this->orderTransactionRepository);
        $this->context = Context::createDefaultContext();
    }

    public function testReadOneById(): void
    {
        $orderId = OrderFixture::ORDER_ID_1;
        $this->ensureOrderExists($orderId);

        $transactionId = Uuid::randomHex();
        $this->createOrderTransaction($transactionId, $orderId);

        $transaction = $this->reader->readOneById($transactionId, $this->context);

        $this->assertInstanceOf(OrderTransactionEntity::class, $transaction);
        $this->assertSame($transactionId, $transaction->getId());
        $this->assertSame($orderId, $transaction->getOrderId());
    }

    public function testReadByIds(): void
    {
        $orderId = OrderFixture::ORDER_ID_1;
        $this->ensureOrderExists($orderId);

        $transactionId = Uuid::randomHex();
        $this->createOrderTransaction($transactionId, $orderId);

        $collection = $this->reader->readByIds([$transactionId], $this->context);

        $this->assertInstanceOf(OrderTransactionCollection::class, $collection);
        $this->assertCount(1, $collection);
        $this->assertTrue($collection->has($transactionId));
    }

    public function testReadAll(): void
    {
        $orderId = OrderFixture::ORDER_ID_1;
        $this->ensureOrderExists($orderId);

        $transactionId = Uuid::randomHex();
        $this->createOrderTransaction($transactionId, $orderId);

        $criteria = new Criteria([$transactionId]);
        $all = $this->reader->readAll($this->context, $criteria);

        $this->assertCount(1, $all);
        $this->assertTrue($all->has($transactionId));
    }

    private function createOrderTransaction(string $id, string $orderId): void
    {
        $paymentMethodId = $this->getValidPaymentMethodId();
        $stateId = $this->getContainer()->get(InitialStateIdLoader::class)->get(OrderTransactionStates::STATE_MACHINE);

        $this->orderTransactionRepository->create([
            [
                'id' => $id,
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
            ],
        ], $this->context);
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
