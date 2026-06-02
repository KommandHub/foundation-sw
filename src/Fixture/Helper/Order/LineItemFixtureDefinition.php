<?php

declare(strict_types=1);

namespace Kommandhub\Foundation\Fixture\Helper\Order;

class LineItemFixtureDefinition
{
    private ?string $id = null;

    private ?string $productId = null;

    private ?string $referencedId = null;

    private string $type = 'product';

    private ?string $label = null;

    private int $quantity = 1;

    private ?int $position = null;

    private ?array $price = null;

    private ?array $priceDefinition = null;

    private array $payload = [];

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

    public function productId(string $productId): self
    {
        $this->productId = $productId;
        $this->referencedId = $productId;

        return $this;
    }

    public function getProductId(): ?string
    {
        return $this->productId;
    }

    public function referencedId(string $referencedId): self
    {
        $this->referencedId = $referencedId;

        return $this;
    }

    public function getReferencedId(): ?string
    {
        return $this->referencedId;
    }

    public function type(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function label(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function quantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function position(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function price(array $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getPrice(): ?array
    {
        return $this->price;
    }

    public function priceDefinition(array $priceDefinition): self
    {
        $this->priceDefinition = $priceDefinition;

        return $this;
    }

    public function getPriceDefinition(): ?array
    {
        return $this->priceDefinition;
    }

    public function payload(array $payload): self
    {
        $this->payload = $payload;

        return $this;
    }

    public function getPayload(): array
    {
        return $this->payload;
    }

    public function customFields(array $customFields): self
    {
        $this->customFields = $customFields;

        return $this;
    }

    public function getCustomFields(): array
    {
        return $this->customFields;
    }

    public function toArray(): array
    {
        $label = $this->label ?? 'Product ' . ($this->referencedId ?? $this->productId ?? 'Unknown');

        $unitPrice = 0.0;
        $totalPrice = 0.0;

        if ($this->price !== null) {
            $unitPrice = $this->price['unitPrice'] ?? $this->price['totalPrice'] ?? 0.0;
            $totalPrice = $this->price['totalPrice'] ?? ($unitPrice * $this->quantity);
        }

        $data = [
            'type' => $this->type,
            'quantity' => $this->quantity,
            'label' => $label,
            'payload' => $this->payload,
            'customFields' => $this->customFields,
            'price' => [
                'unitPrice' => $unitPrice,
                'totalPrice' => $totalPrice,
                'quantity' => $this->quantity,
                'calculatedTaxes' => $this->price['calculatedTaxes'] ?? [],
                'taxRules' => $this->price['taxRules'] ?? [],
            ],
        ];

        if ($this->id !== null) {
            $data['id'] = $this->id;
        }

        if ($this->productId !== null) {
            $data['productId'] = $this->productId;
        }

        if ($this->referencedId !== null) {
            $data['referencedId'] = $this->referencedId;
        }

        if ($this->position !== null) {
            $data['position'] = $this->position;
        }

        if ($this->priceDefinition !== null) {
            $data['priceDefinition'] = $this->priceDefinition;
        }

        return $data;
    }
}
