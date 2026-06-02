<?php

declare(strict_types=1);

namespace Kommandhub\Foundation\Service\Entities;

use Kommandhub\Foundation\EntityHandler\OrderTransaction\OrderTransactionReader;
use Kommandhub\Foundation\EntityHandler\OrderTransaction\OrderTransactionWriter;
use Shopware\Core\Checkout\Order\Aggregate\OrderTransaction\OrderTransactionEntity;
use Shopware\Core\Checkout\Payment\PaymentException;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;

/**
 * Service for managing Order Transaction entities.
 *
 * Provides methods for fetching and updating Order Transactions
 * using specialized reader and writer components.
 */
class OrderTransactionService
{
    /**
     * @param OrderTransactionReader $orderTransactionReader
     * @param OrderTransactionWriter $orderTransactionWriter
     */
    public function __construct(
        private readonly OrderTransactionReader $orderTransactionReader,
        private readonly OrderTransactionWriter $orderTransactionWriter,
    ) {
    }

    /**
     * Fetches an Order Transaction by its identifier.
     *
     * @param string $transactionId The transaction identifier
     * @param Context $context Shopware execution context
     *
     * @return OrderTransactionEntity The found transaction
     *
     * @throws PaymentException If the transaction cannot be found
     */
    public function fetchById(string $transactionId, Context $context): OrderTransactionEntity
    {
        $orderTransaction = $this->orderTransactionReader->readOneById(
            $transactionId,
            $context,
            $this->getCriteria()
        );

        if (!$orderTransaction instanceof OrderTransactionEntity) {
            throw PaymentException::asyncProcessInterrupted(
                $transactionId,
                sprintf('Order transaction "%s" could not be found.', $transactionId)
            );
        }

        return $orderTransaction;
    }

    /**
     * Updates the custom fields of an Order Transaction.
     *
     * @param string $transactionId The transaction identifier
     * @param array<string, mixed> $customFields Data to update in custom fields
     * @param Context $context Shopware execution context
     */
    public function updateCustomFields(string $transactionId, array $customFields, Context $context): void
    {
        $this->orderTransactionWriter->write([
            'id' => $transactionId,
            'customFields' => $customFields,
        ], $context);
    }

    /**
     * Returns the criteria for fetching Order Transactions with necessary associations.
     *
     * @return Criteria
     */
    private function getCriteria(): Criteria
    {
        $criteria = new Criteria();
        $criteria->addAssociations([
            'order.currency',
            'order.lineItems',
            'order.orderCustomer.salutation',
        ]);

        return $criteria;
    }
}
