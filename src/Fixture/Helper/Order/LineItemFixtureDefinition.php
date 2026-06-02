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

    /**
     * @var array<string, mixed>|null
     */
    private ?array $price = null;

    /**
     * @var array<string, mixed>|null
     */
    private ?array $priceDefinition = null;

    /**
     * @var array<string, mixed>
     */
    private array $payload = [];

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

    /**
     * @param array<string, mixed> $price
     */
    public function price(array $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getPrice(): ?array
    {
        return $this->price;
    }

    /**
     * @param array<string, mixed> $priceDefinition
     */
    public function priceDefinition(array $priceDefinition): self
    {
        $this->priceDefinition = $priceDefinition;

        return $this;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getPriceDefinition(): ?array
    {
        return $this->priceDefinition;
    }

    /**
     * @param array<string, mixed> $payload
     */
    public function payload(array $payload): self
    {
        $this->payload = $payload;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function getPayload(): array
    {
        return $this->payload;
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
        $label = $this->label ?? 'Product ' . ($this->referencedId ?? $this->productId ?? 'Unknown');

        $unitPrice = 0.0;
        $totalPrice = 0.0;

        if ($this->price !== null) {
            $unitPriceValue = $this->price['unitPrice'] ?? $this->price['totalPrice'] ?? 0.0;
            $unitPrice = is_numeric($unitPriceValue) ? (float)$unitPriceValue : 0.0;

            $totalPriceValue = $this->price['totalPrice'] ?? ($unitPrice * $this->quantity);
            $totalPrice = is_numeric($totalPriceValue) ? (float)$totalPriceValue : 0.0;
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
