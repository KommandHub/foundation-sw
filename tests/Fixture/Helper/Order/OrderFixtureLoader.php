<?php

declare(strict_types=1);

namespace Kommandhub\Foundation\Tests\Fixture\Helper\Order;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\System\SalesChannel\SalesChannelEntity;
use Shopware\Core\System\StateMachine\Loader\InitialStateIdLoader;
use Shopware\Core\Test\TestDefaults;

class OrderFixtureLoader
{
    /**
     * @param EntityRepository<\Shopware\Core\Framework\DataAbstractionLayer\EntityCollection<\Shopware\Core\Checkout\Order\OrderEntity>> $orderRepository
     * @param EntityRepository<\Shopware\Core\Framework\DataAbstractionLayer\EntityCollection<\Shopware\Core\System\SalesChannel\SalesChannelEntity>> $salesChannelRepository
     * @param EntityRepository<\Shopware\Core\Framework\DataAbstractionLayer\EntityCollection<\Shopware\Core\System\Salutation\SalutationEntity>> $salutationRepository
     */
    public function __construct(
        private readonly EntityRepository $orderRepository,
        private readonly EntityRepository $salesChannelRepository,
        private readonly InitialStateIdLoader $initialStateIdLoader,
        private readonly EntityRepository $salutationRepository
    ) {
    }

    public function apply(OrderFixtureDefinition $orderFixtureDefinition): void
    {
        $context = Context::createDefaultContext();

        $this->orderRepository->upsert(
            [
                $orderFixtureDefinition->toArray(),
            ],
            $context
        );
    }

    public function getInitialStateId(string $stateMachine): string
    {
        return $this->initialStateIdLoader->get($stateMachine);
    }

    public function getSalesChannel(Context $context): SalesChannelEntity
    {
        $salesChannel = $this->salesChannelRepository
            ->search(
                new Criteria([TestDefaults::SALES_CHANNEL]),
                $context
            )
            ->first();

        if (!$salesChannel instanceof SalesChannelEntity) {
            throw new \RuntimeException(
                sprintf(
                    'Sales channel "%s" not found.',
                    TestDefaults::SALES_CHANNEL
                )
            );
        }

        return $salesChannel;
    }

    public function getDefaultSalutationId(Context $context): string
    {
        $criteria = new Criteria();
        $criteria->setLimit(1);

        $id = $this->salutationRepository->searchIds($criteria, $context)->firstId();

        if (!$id) {
            throw new \RuntimeException('No salutation found.');
        }

        return $id;
    }
}
