<?php

declare(strict_types=1);

namespace Kommandhub\Foundation\Fixture\Helper\Order;

use Shopware\Core\Defaults;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\Test\TestDefaults;
use Shopware\FixtureBundle\Helper\Customer\CustomerFixtureDefinition;

class OrderFixtureDefinition
{
    private ?string $id = null;

    private ?string $orderNumber = null;

    private ?string $billingAddressId = null;

    private ?string $currencyId = null;

    private float $currencyFactor = 1.0;

    private ?string $salesChannelId = null;

    private ?string $orderDateTime = null;

    private float $totalPrice = 0.0;

    private float $netPrice = 0.0;

    private float $shippingCosts = 0.0;

    private string $taxStatus = 'gross';

    private ?string $orderState = null;

    private ?string $stateId = null;

    private ?string $paymentState = null;

    private ?string $deliveryState = null;

    private ?CustomerFixtureDefinition $orderCustomer = null;

    private ?string $salutationId = null;

    /**
     * @var LineItemFixtureDefinition[]
     */
    private array $lineItems = [];

    /**
     * @var DeliveryFixtureDefinition[]
     */
    private array $deliveries = [];

    /**
     * @var TransactionFixtureDefinition[]
     */
    private array $transactions = [];

    private ?OrderAddressFixtureDefinition $billingAddress = null;

    private ?OrderAddressFixtureDefinition $shippingAddress = null;

    private array $customFields = [];

    private array $itemRounding = [
        'decimals' => 2,
        'interval' => 0.01,
        'roundForNet' => true,
    ];

    private array $totalRounding = [
        'decimals' => 2,
        'interval' => 0.01,
        'roundForNet' => true,
    ];

    public function __construct(string $orderNumber)
    {
        $this->orderNumber = $orderNumber;
    }

    public function id(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function orderNumber(string $orderNumber): self
    {
        $this->orderNumber = $orderNumber;

        return $this;
    }

    public function getOrderNumber(): ?string
    {
        return $this->orderNumber;
    }

    public function billingAddressId(string $billingAddressId): self
    {
        $this->billingAddressId = $billingAddressId;

        return $this;
    }

    public function getBillingAddressId(): ?string
    {
        return $this->billingAddressId;
    }

    public function currencyId(string $currencyId): self
    {
        $this->currencyId = $currencyId;

        return $this;
    }

    public function getCurrencyId(): ?string
    {
        return $this->currencyId;
    }

    public function currencyFactor(float $currencyFactor): self
    {
        $this->currencyFactor = $currencyFactor;

        return $this;
    }

    public function getCurrencyFactor(): float
    {
        return $this->currencyFactor;
    }

    public function salesChannelId(string $salesChannelId): self
    {
        $this->salesChannelId = $salesChannelId;

        return $this;
    }

    public function getSalesChannelId(): ?string
    {
        return $this->salesChannelId;
    }

    public function orderDateTime(string $orderDateTime): self
    {
        $this->orderDateTime = $orderDateTime;

        return $this;
    }

    public function getOrderDateTime(): ?string
    {
        return $this->orderDateTime;
    }

    public function totalPrice(float $totalPrice): self
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    public function getTotalPrice(): float
    {
        return $this->totalPrice;
    }

    public function netPrice(float $netPrice): self
    {
        $this->netPrice = $netPrice;

        return $this;
    }

    public function getNetPrice(): float
    {
        return $this->netPrice;
    }

    public function shippingCosts(float $shippingCosts): self
    {
        $this->shippingCosts = $shippingCosts;

        return $this;
    }

    public function getShippingCosts(): float
    {
        return $this->shippingCosts;
    }

    public function taxStatus(string $taxStatus): self
    {
        $this->taxStatus = $taxStatus;

        return $this;
    }

    public function getTaxStatus(): string
    {
        return $this->taxStatus;
    }

    public function orderState(string $orderState): self
    {
        $this->orderState = $orderState;

        return $this;
    }

    public function getOrderState(): ?string
    {
        return $this->orderState;
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

    public function paymentState(string $paymentState): self
    {
        $this->paymentState = $paymentState;

        return $this;
    }

    public function getPaymentState(): ?string
    {
        return $this->paymentState;
    }

    public function deliveryState(string $deliveryState): self
    {
        $this->deliveryState = $deliveryState;

        return $this;
    }

    public function getDeliveryState(): ?string
    {
        return $this->deliveryState;
    }

    public function orderCustomer(CustomerFixtureDefinition $orderCustomer): self
    {
        $this->orderCustomer = $orderCustomer;

        return $this;
    }

    public function getOrderCustomer(): ?CustomerFixtureDefinition
    {
        return $this->orderCustomer;
    }

    /**
     * @return LineItemFixtureDefinition[]
     */
    public function getLineItems(): array
    {
        return $this->lineItems;
    }

    public function addLineItem(LineItemFixtureDefinition $lineItem): self
    {
        $this->lineItems[] = $lineItem;

        return $this;
    }

    /**
     * @return DeliveryFixtureDefinition[]
     */
    public function getDeliveries(): array
    {
        return $this->deliveries;
    }

    public function addDelivery(DeliveryFixtureDefinition $delivery): self
    {
        $this->deliveries[] = $delivery;

        return $this;
    }

    /**
     * @return TransactionFixtureDefinition[]
     */
    public function getTransactions(): array
    {
        return $this->transactions;
    }

    public function addTransaction(TransactionFixtureDefinition $transaction): self
    {
        $this->transactions[] = $transaction;

        return $this;
    }

    public function billingAddress(OrderAddressFixtureDefinition $billingAddress): self
    {
        $this->billingAddress = $billingAddress;

        return $this;
    }

    public function getBillingAddress(): ?OrderAddressFixtureDefinition
    {
        return $this->billingAddress;
    }

    public function shippingAddress(OrderAddressFixtureDefinition $shippingAddress): self
    {
        $this->shippingAddress = $shippingAddress;

        return $this;
    }

    public function getShippingAddress(): ?OrderAddressFixtureDefinition
    {
        return $this->shippingAddress;
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

    public function itemRounding(array $itemRounding): self
    {
        $this->itemRounding = $itemRounding;

        return $this;
    }

    public function getItemRounding(): array
    {
        return $this->itemRounding;
    }

    public function totalRounding(array $totalRounding): self
    {
        $this->totalRounding = $totalRounding;

        return $this;
    }

    public function getTotalRounding(): array
    {
        return $this->totalRounding;
    }

    public function salutationId(string $salutationId): self
    {
        $this->salutationId = $salutationId;

        return $this;
    }

    public function getSalutationId(): ?string
    {
        return $this->salutationId;
    }

    public function toArray(): array
    {
        $totalPrice = $this->totalPrice;
        $positionPrice = 0.0;

        if (!empty($this->lineItems)) {
            $sum = 0.0;

            foreach ($this->lineItems as $item) {
                $price = $item->getPrice();

                if ($price && isset($price['totalPrice'])) {
                    $sum += $price['totalPrice'];
                }
            }
            $positionPrice = $sum;

            if ($totalPrice === 0.0) {
                $totalPrice = $sum;
            }
        }

        $netPrice = $this->netPrice;

        if ($netPrice === 0.0 && $totalPrice > 0.0) {
            $netPrice = $totalPrice / 1.19; // Simple assumption if not provided
        }

        $data = [
            'currencyFactor' => $this->currencyFactor,
            'shippingCosts' => [
                'unitPrice' => $this->shippingCosts,
                'totalPrice' => $this->shippingCosts,
                'quantity' => 1,
                'calculatedTaxes' => [],
                'taxRules' => [],
            ],
            'price' => [
                'netPrice' => $netPrice,
                'totalPrice' => $totalPrice,
                'positionPrice' => $positionPrice,
                'rawTotal' => $totalPrice,
                'taxStatus' => $this->taxStatus,
                'calculatedTaxes' => [],
                'taxRules' => [],
            ],
            'customFields' => $this->customFields,
            'itemRounding' => $this->itemRounding,
            'totalRounding' => $this->totalRounding,
            'lineItems' => array_map(fn (LineItemFixtureDefinition $item) => $item->toArray(), $this->lineItems),
            'deliveries' => array_map(fn (DeliveryFixtureDefinition $delivery) => $delivery->toArray(), $this->deliveries),
            'transactions' => array_map(fn (TransactionFixtureDefinition $transaction) => $transaction->toArray($totalPrice), $this->transactions),
        ];

        if ($this->orderCustomer !== null) {
            $data['orderCustomer'] = [
                'email' => $this->orderCustomer->getEmail(),
                'firstName' => $this->orderCustomer->getFirstName() ?? 'Test',
                'lastName' => $this->orderCustomer->getLastName() ?? 'Customer',
                'salutationId' => $this->salutationId ?? Uuid::randomHex(),
                'customerNumber' => $this->orderCustomer->getCustomerNumber() ?? Uuid::randomHex(),
            ];
        }

        if ($this->id !== null) {
            $data['id'] = $this->id;
        }

        $data['orderNumber'] = $this->orderNumber;

        $data['currencyId'] = $this->currencyId ?? Defaults::CURRENCY;
        $data['salesChannelId'] = $this->salesChannelId ?? TestDefaults::SALES_CHANNEL;
        $data['languageId'] = Defaults::LANGUAGE_SYSTEM;

        $data['orderDateTime'] = $this->orderDateTime ?? (new \DateTime())->format('Y-m-d H:i:s');
        $data['orderState'] = $this->orderState ?? 'open';

        if ($this->stateId !== null) {
            $data['stateId'] = $this->stateId;
        }

        $data['paymentState'] = $this->paymentState ?? 'open';
        $data['deliveryState'] = $this->deliveryState ?? 'open';

        if ($this->billingAddress !== null) {
            $billingAddressData = $this->billingAddress->toArray();

            if (!isset($billingAddressData['id'])) {
                $billingAddressData['id'] = Uuid::randomHex();
            }
            $data['billingAddress'] = $billingAddressData;
            $data['billingAddressId'] = $billingAddressData['id'];
        } elseif ($this->billingAddressId !== null) {
            $data['billingAddressId'] = $this->billingAddressId;
        } else {
            // Generate a default billing address if none provided
            $defaultAddress = new OrderAddressFixtureDefinition();
            $addressData = $defaultAddress->toArray();
            $addressData['id'] = Uuid::randomHex();
            $data['billingAddress'] = $addressData;
            $data['billingAddressId'] = $addressData['id'];
        }

        if ($this->shippingAddress !== null) {
            $data['shippingAddress'] = $this->shippingAddress->toArray();
        }

        return $data;
    }
}
