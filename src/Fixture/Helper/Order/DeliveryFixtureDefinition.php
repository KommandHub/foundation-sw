<?php

declare(strict_types=1);

namespace Kommandhub\Foundation\Fixture\Helper\Order;

class DeliveryFixtureDefinition
{
    private ?string $id = null;

    private ?string $shippingMethodId = null;

    private ?string $shippingMethodName = null;

    private ?string $stateId = null;

    private ?string $stateName = null;

    private ?OrderAddressFixtureDefinition $shippingOrderAddress = null;

    private ?string $shippingDateEarliest = null;

    private ?string $shippingDateLatest = null;

    /**
     * @var array<string, mixed>|null
     */
    private ?array $shippingCosts = null;

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

    public function shippingMethodId(string $shippingMethodId): self
    {
        $this->shippingMethodId = $shippingMethodId;

        return $this;
    }

    public function getShippingMethodId(): ?string
    {
        return $this->shippingMethodId;
    }

    public function shippingMethodName(string $shippingMethodName): self
    {
        $this->shippingMethodName = $shippingMethodName;

        return $this;
    }

    public function getShippingMethodName(): ?string
    {
        return $this->shippingMethodName;
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

    public function shippingOrderAddress(OrderAddressFixtureDefinition $shippingOrderAddress): self
    {
        $this->shippingOrderAddress = $shippingOrderAddress;

        return $this;
    }

    public function getShippingOrderAddress(): ?OrderAddressFixtureDefinition
    {
        return $this->shippingOrderAddress;
    }

    public function shippingDateEarliest(string $shippingDateEarliest): self
    {
        $this->shippingDateEarliest = $shippingDateEarliest;

        return $this;
    }

    public function getShippingDateEarliest(): ?string
    {
        return $this->shippingDateEarliest;
    }

    public function shippingDateLatest(string $shippingDateLatest): self
    {
        $this->shippingDateLatest = $shippingDateLatest;

        return $this;
    }

    public function getShippingDateLatest(): ?string
    {
        return $this->shippingDateLatest;
    }

    /**
     * @param array<string, mixed> $shippingCosts
     */
    public function shippingCosts(array $shippingCosts): self
    {
        $this->shippingCosts = $shippingCosts;

        return $this;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getShippingCosts(): ?array
    {
        return $this->shippingCosts;
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
    public function toArray(): array
    {
        $data = [
            'customFields' => $this->customFields,
        ];

        if ($this->id !== null) {
            $data['id'] = $this->id;
        }

        $data['shippingMethodName'] = $this->shippingMethodName ?? 'Standard';

        if ($this->shippingMethodId !== null) {
            $data['shippingMethodId'] = $this->shippingMethodId;
        }

        $data['stateName'] = $this->stateName ?? 'open';

        if ($this->stateId !== null) {
            $data['stateId'] = $this->stateId;
        }

        if ($this->shippingOrderAddress !== null) {
            $data['shippingOrderAddress'] = $this->shippingOrderAddress->toArray();
        }

        $data['shippingDateEarliest'] = $this->shippingDateEarliest ?? (new \DateTime())->format('Y-m-d H:i:s');
        $data['shippingDateLatest'] = $this->shippingDateLatest ?? (new \DateTime())->format('Y-m-d H:i:s');

        $shippingCostsPrice = $this->shippingCosts ?? ['totalPrice' => 0.0, 'unitPrice' => 0.0];
        $data['shippingCosts'] = [
            'unitPrice' => $shippingCostsPrice['unitPrice'] ?? $shippingCostsPrice['totalPrice'] ?? 0.0,
            'totalPrice' => $shippingCostsPrice['totalPrice'] ?? 0.0,
            'quantity' => 1,
            'calculatedTaxes' => $shippingCostsPrice['calculatedTaxes'] ?? [],
            'taxRules' => $shippingCostsPrice['taxRules'] ?? [],
        ];

        return $data;
    }
}
