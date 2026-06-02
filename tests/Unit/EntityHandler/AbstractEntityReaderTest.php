<?php

declare(strict_types=1);

namespace Kommandhub\Foundation\Tests\Unit\EntityHandler;

use Kommandhub\Foundation\EntityHandler\AbstractEntityReader;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\EntitySearchResult;

class AbstractEntityReaderTest extends TestCase
{
    private EntityRepository&MockObject $repository;
    private AbstractEntityReader $reader;
    private Context $context;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(EntityRepository::class);
        $this->context = Context::createDefaultContext();

        // Using anonymous class to test abstract class
        $this->reader = new class($this->repository) extends AbstractEntityReader {
            protected function getRepository(): EntityRepository
            {
                return $this->repository;
            }
        };
    }

    public function testReadAll(): void
    {
        $criteria = new Criteria();
        $collection = new EntityCollection();

        $result = $this->createMock(EntitySearchResult::class);
        $result->method('getEntities')->willReturn($collection);

        $this->repository->expects($this->once())
            ->method('search')
            ->with($this->callback(function (Criteria $c) {
                return $c !== null;
            }), $this->context)
            ->willReturn($result);

        $returnedCollection = $this->reader->readAll($this->context, $criteria);
        $this->assertSame($collection, $returnedCollection);
    }

    public function testCount(): void
    {
        $criteria = new Criteria();

        $result = $this->createMock(EntitySearchResult::class);
        $result->method('getTotal')->willReturn(5);

        $this->repository->expects($this->once())
            ->method('search')
            ->with($this->callback(function (Criteria $c) {
                return $c->getLimit() === 1 && $c->getTotalCountMode() === Criteria::TOTAL_COUNT_MODE_EXACT;
            }), $this->context)
            ->willReturn($result);

        $count = $this->reader->count($this->context, $criteria);
        $this->assertEquals(5, $count);
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

    public function testReadByIds(): void
    {
        $ids = ['id1', 'id2'];
        $collection = new EntityCollection();

        $result = $this->createMock(EntitySearchResult::class);
        $result->method('getEntities')->willReturn($collection);

        $this->repository->expects($this->once())
            ->method('search')
            ->with($this->callback(function (Criteria $c) use ($ids) {
                return $c->getIds() === $ids;
            }), $this->context)
            ->willReturn($result);

        $returnedCollection = $this->reader->readByIds($ids, $this->context);
        $this->assertSame($collection, $returnedCollection);
    }

    public function testReadByIdsWithEmptyArray(): void
    {
        $this->repository->expects($this->never())->method('search');

        $returnedCollection = $this->reader->readByIds([], $this->context);
        $this->assertInstanceOf(EntityCollection::class, $returnedCollection);
        $this->assertCount(0, $returnedCollection);
    }

    public function testWithAssociations(): void
    {
        $associations = ['assoc1', 'assoc2'];

        // Use a reflection to access protected method or test via public method that uses it if any
        // Since we are in an anonymous class in setUp, we can just add a public wrapper

        $reader = new class($this->repository) extends AbstractEntityReader {
            public function getRepository(): EntityRepository
            {
                return $this->repository;
            }

            public function testWrapperWithAssociations(array $associations, ?Criteria $criteria = null): Criteria
            {
                return $this->withAssociations($associations, $criteria);
            }
        };

        $criteria = $reader->testWrapperWithAssociations($associations);
        $this->assertArrayHasKey('assoc1', $criteria->getAssociations());
        $this->assertArrayHasKey('assoc2', $criteria->getAssociations());
    }

    public function testGetRepository(): void
    {
        $reflection = new \ReflectionClass($this->reader);
        $method = $reflection->getMethod('getRepository');
        $method->setAccessible(true);

        $returnedRepository = $method->invoke($this->reader);
        $this->assertSame($this->repository, $returnedRepository);
    }
}
