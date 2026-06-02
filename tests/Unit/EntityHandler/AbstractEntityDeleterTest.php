<?php

declare(strict_types=1);

namespace Kommandhub\Foundation\Tests\Unit\EntityHandler;

use Kommandhub\Foundation\EntityHandler\AbstractEntityDeleter;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;

class AbstractEntityDeleterTest extends TestCase
{
    private EntityRepository&MockObject $repository;
    private AbstractEntityDeleter $deleter;
    private Context $context;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(EntityRepository::class);
        $this->context = Context::createDefaultContext();

        $this->deleter = new class($this->repository) extends AbstractEntityDeleter {
        };
    }

    public function testDelete(): void
    {
        $id = 'test-id';

        $this->repository->expects($this->once())
            ->method('delete')
            ->with([['id' => $id]], $this->context);

        $this->deleter->delete($id, $this->context);
    }

    public function testDeleteMany(): void
    {
        $ids = ['id1', 'id2'];

        $this->repository->expects($this->once())
            ->method('delete')
            ->with([['id' => 'id1'], ['id' => 'id2']], $this->context);

        $this->deleter->deleteMany($ids, $this->context);
    }

    public function testDeleteManyWithEmptyArray(): void
    {
        $this->repository->expects($this->never())->method('delete');

        $this->deleter->deleteMany([], $this->context);
    }
}
