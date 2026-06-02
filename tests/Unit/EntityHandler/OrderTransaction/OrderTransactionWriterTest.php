<?php

declare(strict_types=1);

namespace Kommandhub\Foundation\Tests\Unit\EntityHandler\OrderTransaction;

use Kommandhub\Foundation\EntityHandler\OrderTransaction\OrderTransactionWriter;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\Uuid\Uuid;

#[CoversClass(OrderTransactionWriter::class)]
class OrderTransactionWriterTest extends TestCase
{
    private EntityRepository&MockObject $repository;
    private OrderTransactionWriter $writer;
    private Context $context;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(EntityRepository::class);
        $this->writer = new OrderTransactionWriter($this->repository);
        $this->context = Context::createDefaultContext();
    }

    public function testGetRepository(): void
    {
        $reflection = new \ReflectionClass(OrderTransactionWriter::class);
        $method = $reflection->getMethod('getRepository');

        $returnedRepository = $method->invoke($this->writer);
        $this->assertSame($this->repository, $returnedRepository);
    }

    public function testWrite(): void
    {
        $id = Uuid::randomHex();
        $data = [
            'id' => $id,
            'orderId' => Uuid::randomHex(),
        ];

        $this->repository->expects($this->once())
            ->method('upsert')
            ->with([$data], $this->context);

        $returnedId = $this->writer->write($data, $this->context);
        $this->assertSame($id, $returnedId);
    }

    public function testWriteMany(): void
    {
        $rows = [
            ['id' => Uuid::randomHex()],
            ['id' => Uuid::randomHex()],
        ];

        $this->repository->expects($this->once())
            ->method('upsert')
            ->with($rows, $this->context);

        $returnedIds = $this->writer->writeMany($rows, $this->context);
        $this->assertCount(2, $returnedIds);
        $this->assertSame($rows[0]['id'], $returnedIds[0]);
    }
}
