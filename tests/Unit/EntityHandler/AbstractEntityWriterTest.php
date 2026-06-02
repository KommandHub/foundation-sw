<?php

declare(strict_types=1);

namespace Kommandhub\Foundation\Tests\Unit\EntityHandler;

use Kommandhub\Foundation\EntityHandler\AbstractEntityWriter;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;

#[CoversClass(AbstractEntityWriter::class)]
class AbstractEntityWriterTest extends TestCase
{
    private EntityRepository&MockObject $repository;
    private AbstractEntityWriter $writer;
    private Context $context;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(EntityRepository::class);
        $this->context = Context::createDefaultContext();

        $this->writer = new class($this->repository) extends AbstractEntityWriter {
        };
    }

    public function testWrite(): void
    {
        $data = ['name' => 'Test Entity'];

        $this->repository->expects($this->once())
            ->method('upsert')
            ->with($this->callback(function (array $rows) {
                return count($rows) === 1 && isset($rows[0]['id']);
            }), $this->context);

        $id = $this->writer->write($data, $this->context);
        $this->assertIsString($id);
    }

    public function testWriteWithProvidedId(): void
    {
        $id = 'provided-id';
        $data = ['id' => $id, 'name' => 'Test Entity'];

        $this->repository->expects($this->once())
            ->method('upsert')
            ->with([$data], $this->context);

        $returnedId = $this->writer->write($data, $this->context);
        $this->assertEquals($id, $returnedId);
    }

    public function testWriteMany(): void
    {
        $rows = [
            ['name' => 'Entity 1'],
            ['name' => 'Entity 2'],
        ];

        $this->repository->expects($this->once())
            ->method('upsert')
            ->with($this->callback(function (array $upsertRows) {
                return count($upsertRows) === 2 && isset($upsertRows[0]['id']) && isset($upsertRows[1]['id']);
            }), $this->context);

        $ids = $this->writer->writeMany($rows, $this->context);
        $this->assertCount(2, $ids);
        $this->assertIsString($ids[0]);
        $this->assertIsString($ids[1]);
    }

    public function testWriteManyWithEmptyArray(): void
    {
        $this->repository->expects($this->never())->method('upsert');

        $ids = $this->writer->writeMany([], $this->context);
        $this->assertEquals([], $ids);
    }

    public function testWriteWithInvalidIdType(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Entity ID must be a string');

        $this->writer->write(['id' => 123], $this->context);
    }

    public function testWriteManyWithInvalidIdType(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Entity ID must be a string');

        $this->writer->writeMany([['id' => 123]], $this->context);
    }
}
