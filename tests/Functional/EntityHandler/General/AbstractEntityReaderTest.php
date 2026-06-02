<?php

declare(strict_types=1);

namespace Kommandhub\Foundation\Tests\Functional\EntityHandler\General;

use Kommandhub\Foundation\EntityHandler\AbstractEntityReader;
use Kommandhub\Foundation\EntityHandler\AbstractEntityWriter;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Checkout\Order\OrderCollection;
use Shopware\Core\Checkout\Order\OrderEntity;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\Test\TestCaseBase\IntegrationTestBehaviour;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\StateMachine\Loader\InitialStateIdLoader;

class AbstractEntityReaderTest extends TestCase
{
    use IntegrationTestBehaviour;

    private EntityRepository $orderRepository;
    private Context $context;

    /** @var AbstractEntityReader<OrderEntity, OrderCollection> */
    private AbstractEntityReader $reader;

    /** @var AbstractEntityWriter<OrderCollection> */
    private AbstractEntityWriter $writer;

    protected function setUp(): void
    {
        $this->orderRepository = $this->getContainer()->get('order.repository');
        $this->context = Context::createDefaultContext();

        // Create concrete implementations for testing
        $this->reader = new class($this->orderRepository) extends AbstractEntityReader {
            protected function getRepository(): EntityRepository
            {
                return $this->repository;
            }
        };

        $this->writer = new class($this->orderRepository) extends AbstractEntityWriter {
        };
    }

    public function testReadOneById(): void
    {
        $id = Uuid::randomHex();
        $data = $this->getOrderData($id, 'TEST-ORDER-READ-1');

        $this->writer->write($data, $this->context);

        $order = $this->reader->readOneById($id, $this->context);
        $this->assertInstanceOf(OrderEntity::class, $order);
        $this->assertSame($id, $order->getId());
        $this->assertSame('TEST-ORDER-READ-1', $order->getOrderNumber());
    }

    public function testCount(): void
    {
        $id = Uuid::randomHex();
        $data = $this->getOrderData($id, 'TEST-ORDER-COUNT');
        $this->writer->write($data, $this->context);

        $count = $this->reader->count($this->context, new Criteria([$id]));
        $this->assertSame(1, $count);
    }

    public function testReadByIds(): void
    {
        $id = Uuid::randomHex();
        $data = $this->getOrderData($id, 'TEST-ORDER-IDS');
        $this->writer->write($data, $this->context);

        $collection = $this->reader->readByIds([$id], $this->context);
        $this->assertInstanceOf(OrderCollection::class, $collection);
        $this->assertCount(1, $collection);
    }

    public function testReadAll(): void
    {
        $id = Uuid::randomHex();
        $data = $this->getOrderData($id, 'TEST-ORDER-ALL');
        $this->writer->write($data, $this->context);

        $all = $this->reader->readAll($this->context, new Criteria([$id]));
        $this->assertCount(1, $all);
    }

    public function testReadChunks(): void
    {
        $ids = [Uuid::randomHex(), Uuid::randomHex(), Uuid::randomHex()];
        $rows = [
            $this->getOrderData($ids[0], 'BATCH-1'),
            $this->getOrderData($ids[1], 'BATCH-2'),
            $this->getOrderData($ids[2], 'BATCH-3'),
        ];

        $this->writer->writeMany($rows, $this->context);

        $processedIds = [];
        $this->reader->readChunks(
            $this->context,
            function (EntityCollection $entities) use (&$processedIds) {
                foreach ($entities as $entity) {
                    $processedIds[] = $entity->getId();
                }
            },
            new Criteria($ids),
            2
        );

        $this->assertCount(3, $processedIds);

        foreach ($ids as $id) {
            $this->assertContains($id, $processedIds);
        }
    }

    private function getOrderData(string $id, string $orderNumber): array
    {
        $salutationId = $this->getValidSalutationId();

        return [
            'id' => $id,
            'orderNumber' => $orderNumber,
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
                'email' => Uuid::randomHex() . '@example.com',
                'firstName' => 'Test',
                'lastName' => 'Customer',
                'salutationId' => $salutationId,
                'customerNumber' => 'TEST-C-' . Uuid::randomHex(),
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
        ];
    }
}
