<?php

declare(strict_types=1);

namespace Kommandhub\Foundation\Fixture\Helper\Order;

class TransactionFixtureDefinition
{
    private ?string $id = null;

    private ?string $paymentMethodId = null;

    private ?string $paymentMethodName = null;

    private ?string $stateId = null;

    private ?string $stateName = null;

    /**
     * @var array<string, mixed>|null
     */
    private ?array $amount = null;

    /**
     * @var array<string, mixed>
     */
    private array $customFields = [];

    public function id(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function paymentMethodId(string $paymentMethodId): self
    {
        $this->paymentMethodId = $paymentMethodId;

        return $this;
    }

    public function getPaymentMethodId(): ?string
    {
        return $this->paymentMethodId;
    }

    public function paymentMethodName(string $paymentMethodName): self
    {
        $this->paymentMethodName = $paymentMethodName;

        return $this;
    }

    public function getPaymentMethodName(): ?string
    {
        return $this->paymentMethodName;
    }

    public function stateId(string $stateId): self
    {
        $this->stateId = $stateId;

        return $this;
    }

    public function getStateId(): ?string
    {
        return $this->stateId;
    }

    public function stateName(string $stateName): self
    {
        $this->stateName = $stateName;

        return $this;
    }

    public function getStateName(): ?string
    {
        return $this->stateName;
    }

    /**
     * @param array<string, mixed> $amount
     */
    public function amount(array $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getAmount(): ?array
    {
        return $this->amount;
    }

    /**
     * @param array<string, mixed> $customFields
     */
    public function customFields(array $customFields): self
    {
        $this->customFields = $customFields;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function getCustomFields(): array
    {
        return $this->customFields;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(?float $orderTotalPrice = null): array
    {
        $data = [
            'customFields' => $this->customFields,
        ];

        if ($this->id !== null) {
            $data['id'] = $this->id;
        }

        if ($this->paymentMethodId !== null) {
            $data['paymentMethodId'] = $this->paymentMethodId;
        }

        $data['paymentMethodName'] = $this->paymentMethodName ?? 'Paystack';

        if ($this->stateId !== null) {
            $data['stateId'] = $this->stateId;
        }

        $data['stateName'] = $this->stateName ?? 'open';

        $amount = $this->amount;

        if ($amount === null && $orderTotalPrice !== null) {
            $amount = [
                'unitPrice' => $orderTotalPrice,
                'totalPrice' => $orderTotalPrice,
                'quantity' => 1,
                'calculatedTaxes' => [],
                'taxRules' => [],
            ];
        }

        if ($amount !== null) {
            $data['amount'] = $amount;
        }

        return $data;
    }
}
