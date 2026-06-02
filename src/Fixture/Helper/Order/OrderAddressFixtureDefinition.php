<?php

declare(strict_types=1);

namespace Kommandhub\Foundation\Fixture\Helper\Order;

class OrderAddressFixtureDefinition
{
    private ?string $id = null;

    private ?string $salutationId = null;

    private ?string $salutationKey = null;

    private ?string $firstName = null;

    private ?string $lastName = null;

    private ?string $street = null;

    private ?string $zipcode = null;

    private ?string $city = null;

    private ?string $countryId = null;

    private ?string $countryIso = null;

    private ?string $phoneNumber = null;

    private ?string $company = null;

    private ?string $department = null;

    private ?string $additionalAddressLine1 = null;

    private ?string $additionalAddressLine2 = null;

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

    public function salutationId(string $salutationId): self
    {
        $this->salutationId = $salutationId;

        return $this;
    }

    public function getSalutationId(): ?string
    {
        return $this->salutationId;
    }

    public function salutationKey(string $salutationKey): self
    {
        $this->salutationKey = $salutationKey;

        return $this;
    }

    public function getSalutationKey(): ?string
    {
        return $this->salutationKey;
    }

    public function firstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function lastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function street(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function zipcode(string $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function city(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function countryId(string $countryId): self
    {
        $this->countryId = $countryId;

        return $this;
    }

    public function getCountryId(): ?string
    {
        return $this->countryId;
    }

    public function countryIso(string $countryIso): self
    {
        $this->countryIso = $countryIso;

        return $this;
    }

    public function getCountryIso(): ?string
    {
        return $this->countryIso;
    }

    public function phoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function company(string $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function department(string $department): self
    {
        $this->department = $department;

        return $this;
    }

    public function getDepartment(): ?string
    {
        return $this->department;
    }

    public function additionalAddressLine1(string $additionalAddressLine1): self
    {
        $this->additionalAddressLine1 = $additionalAddressLine1;

        return $this;
    }

    public function getAdditionalAddressLine1(): ?string
    {
        return $this->additionalAddressLine1;
    }

    public function additionalAddressLine2(string $additionalAddressLine2): self
    {
        $this->additionalAddressLine2 = $additionalAddressLine2;

        return $this;
    }

    public function getAdditionalAddressLine2(): ?string
    {
        return $this->additionalAddressLine2;
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
        $data = [
            'firstName' => $this->firstName ?? 'Test',
            'lastName' => $this->lastName ?? 'User',
            'street' => $this->street ?? 'Main St 1',
            'zipcode' => $this->zipcode ?? '12345',
            'city' => $this->city ?? 'City',
            'countryIso' => $this->countryIso ?? 'DE',
            'countryId' => $this->countryId ?? '5c8ee7838501460f85c1630c904df62e',
            'customFields' => $this->customFields,
        ];

        if ($this->id !== null) {
            $data['id'] = $this->id;
        }

        if ($this->salutationId !== null) {
            $data['salutationId'] = $this->salutationId;
        }

        if ($this->salutationKey !== null) {
            $data['salutationKey'] = $this->salutationKey;
        }

        if ($this->countryId !== null) {
            $data['countryId'] = $this->countryId;
        }

        if ($this->phoneNumber !== null) {
            $data['phoneNumber'] = $this->phoneNumber;
        }

        if ($this->company !== null) {
            $data['company'] = $this->company;
        }

        if ($this->department !== null) {
            $data['department'] = $this->department;
        }

        if ($this->additionalAddressLine1 !== null) {
            $data['additionalAddressLine1'] = $this->additionalAddressLine1;
        }

        if ($this->additionalAddressLine2 !== null) {
            $data['additionalAddressLine2'] = $this->additionalAddressLine2;
        }

        return $data;
    }
}
