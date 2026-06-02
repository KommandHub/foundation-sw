<?php

declare(strict_types=1);

namespace Kommandhub\Foundation\Tests\Unit\Service\Entities;

use Kommandhub\Foundation\EntityHandler\OrderTransaction\OrderTransactionReader;
use Kommandhub\Foundation\EntityHandler\OrderTransaction\OrderTransactionWriter;
use Kommandhub\Foundation\Service\Entities\OrderTransactionService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Checkout\Order\Aggregate\OrderTransaction\OrderTransactionEntity;
use Shopware\Core\Checkout\Payment\PaymentException;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;

#[CoversClass(OrderTransactionService::class)]
class OrderTransactionServiceTest extends TestCase
{
    private OrderTransactionReader&MockObject $reader;
    private OrderTransactionWriter&MockObject $writer;
    private OrderTransactionService $service;
    private Context $context;

    protected function setUp(): void
    {
        $this->reader = $this->createMock(OrderTransactionReader::class);
        $this->writer = $this->createMock(OrderTransactionWriter::class);
        $this->service = new OrderTransactionService($this->reader, $this->writer);
        $this->context = Context::createDefaultContext();
    }

    public function testFetchByIdSuccess(): void
    {
        $transactionId = 'test-id';
        $transaction = new OrderTransactionEntity();
        $transaction->setId($transactionId);

        $this->reader->expects($this->once())
            ->method('readOneById')
            ->with($transactionId, $this->context, $this->isInstanceOf(Criteria::class))
            ->willReturn($transaction);

        $result = $this->service->fetchById($transactionId, $this->context);
        $this->assertSame($transaction, $result);
    }

    public function testFetchByIdThrowsExceptionWhenNotFound(): void
    {
        $transactionId = 'non-existent-id';

        $this->reader->expects($this->once())
            ->method('readOneById')
            ->willReturn(null);

        $this->expectException(PaymentException::class);
        $this->expectExceptionMessage(sprintf('Order transaction "%s" could not be found.', $transactionId));

        $this->service->fetchById($transactionId, $this->context);
    }

    public function testUpdateCustomFields(): void
    {
        $transactionId = 'test-id';
        $customFields = ['key' => 'value'];

        $this->writer->expects($this->once())
            ->method('write')
            ->with([
                'id' => $transactionId,
                'customFields' => $customFields,
            ], $this->context);

        $this->service->updateCustomFields($transactionId, $customFields, $this->context);
    }
}
