<?php

declare(strict_types=1);

namespace Kommandhub\Foundation\Tests\Functional\EntityHandler\General;

use Kommandhub\Foundation\EntityHandler\AbstractEntityReader;
use Kommandhub\Foundation\EntityHandler\AbstractEntityWriter;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Checkout\Order\OrderCollection;
use Shopware\Core\Checkout\Order\OrderEntity;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\Test\TestCaseBase\IntegrationTestBehaviour;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\StateMachine\Loader\InitialStateIdLoader;

#[CoversClass(AbstractEntityWriter::class)]
#[UsesClass(AbstractEntityReader::class)]
class AbstractEntityWriterTest extends TestCase
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

    public function testWrite(): void
    {
        $id = Uuid::randomHex();
        $data = $this->getOrderData($id, 'TEST-ORDER-WRITE-ONLY');

        $persistedId = $this->writer->write($data, $this->context);
        $this->assertSame($id, $persistedId);

        // Verify with reader
        $order = $this->reader->readOneById($id, $this->context);
        $this->assertNotNull($order);
        $this->assertSame($id, $order->getId());
    }

    public function testWriteMany(): void
    {
        $ids = [Uuid::randomHex(), Uuid::randomHex()];
        $rows = [
            $this->getOrderData($ids[0], 'BATCH-WRITE-1'),
            $this->getOrderData($ids[1], 'BATCH-WRITE-2'),
        ];

        $writtenIds = $this->writer->writeMany($rows, $this->context);
        $this->assertEquals($ids, $writtenIds);

        // Verify with reader
        $collection = $this->reader->readByIds($ids, $this->context);
        $this->assertCount(2, $collection);
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
