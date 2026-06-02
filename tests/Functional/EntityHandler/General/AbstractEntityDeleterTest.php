<?php

declare(strict_types=1);

namespace Kommandhub\Foundation\Tests\Functional\EntityHandler\General;

use Kommandhub\Foundation\EntityHandler\AbstractEntityDeleter;
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

#[CoversClass(AbstractEntityDeleter::class)]
#[UsesClass(AbstractEntityReader::class)]
#[UsesClass(AbstractEntityWriter::class)]
class AbstractEntityDeleterTest extends TestCase
{
    use IntegrationTestBehaviour;

    private EntityRepository $orderRepository;
    private Context $context;

    /** @var AbstractEntityReader<OrderEntity, OrderCollection> */
    private AbstractEntityReader $reader;

    /** @var AbstractEntityWriter<OrderCollection> */
    private AbstractEntityWriter $writer;

    /** @var AbstractEntityDeleter<OrderCollection> */
    private AbstractEntityDeleter $deleter;

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

        $this->deleter = new class($this->orderRepository) extends AbstractEntityDeleter {
        };
    }

    public function testDelete(): void
    {
        $id = Uuid::randomHex();
        $data = $this->getOrderData($id, 'TO-DELETE');
        $this->writer->write($data, $this->context);

        // Verify it exists
        $this->assertNotNull($this->reader->readOneById($id, $this->context));

        // Delete
        $this->deleter->delete($id, $this->context);

        // Verify it's gone
        $this->assertNull($this->reader->readOneById($id, $this->context));
    }

    public function testDeleteMany(): void
    {
        $ids = [Uuid::randomHex(), Uuid::randomHex()];
        $rows = [
            $this->getOrderData($ids[0], 'DEL-1'),
            $this->getOrderData($ids[1], 'DEL-2'),
        ];
        $this->writer->writeMany($rows, $this->context);

        // Verify they exist
        $this->assertCount(2, $this->reader->readByIds($ids, $this->context));

        // Delete Many
        $this->deleter->deleteMany($ids, $this->context);

        // Verify they are gone
        $this->assertCount(0, $this->reader->readByIds($ids, $this->context));
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
