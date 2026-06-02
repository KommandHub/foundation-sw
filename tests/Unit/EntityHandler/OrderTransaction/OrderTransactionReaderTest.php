<?php

declare(strict_types=1);

namespace Kommandhub\Foundation\Tests\Unit\EntityHandler\OrderTransaction;

use Kommandhub\Foundation\EntityHandler\OrderTransaction\OrderTransactionReader;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\EntitySearchResult;

#[CoversClass(OrderTransactionReader::class)]
class OrderTransactionReaderTest extends TestCase
{
    private EntityRepository&MockObject $repository;
    private OrderTransactionReader $reader;
    private Context $context;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(EntityRepository::class);
        $this->reader = new OrderTransactionReader($this->repository);
        $this->context = Context::createDefaultContext();
    }

    public function testGetRepository(): void
    {
        $reflection = new \ReflectionClass(OrderTransactionReader::class);
        $method = $reflection->getMethod('getRepository');

        $returnedRepository = $method->invoke($this->reader);
        $this->assertSame($this->repository, $returnedRepository);
    }

    public function testReadAll(): void
    {
        $criteria = new Criteria();
        $collection = new EntityCollection();

        $result = $this->createMock(EntitySearchResult::class);
        $result->method('getEntities')->willReturn($collection);

        $this->repository->expects($this->once())
            ->method('search')
            ->with($criteria, $this->context)
            ->willReturn($result);

        $returnedCollection = $this->reader->readAll($this->context, $criteria);
        $this->assertSame($collection, $returnedCollection);
    }

    public function testReadOneById(): void
    {
        $id = 'test-id';
        $entity = $this->createMock(Entity::class);

        $result = $this->createMock(EntitySearchResult::class);
        $result->method('first')->willReturn($entity);

        $this->repository->expects($this->once())
            ->method('search')
            ->with($this->callback(function (Criteria $c) use ($id) {
                return $c->getIds() === [$id];
            }), $this->context)
            ->willReturn($result);

        $returnedEntity = $this->reader->readOneById($id, $this->context);
        $this->assertSame($entity, $returnedEntity);
    }
}
